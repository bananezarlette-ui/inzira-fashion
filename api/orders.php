<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD']==='OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) { http_response_code(400); echo json_encode(['error'=>'Invalid JSON']); exit; }
    $name    = trim($data['customer_name']    ?? '');
    $email   = trim($data['customer_email']   ?? '');
    $phone   = trim($data['customer_phone']   ?? '');
    $address = trim($data['customer_address'] ?? '');
    $items   = $data['items'] ?? [];
    if (!$name||!$email||!$phone||!$address||empty($items)) { http_response_code(400); echo json_encode(['error'=>'Missing required fields']); exit; }
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) { http_response_code(400); echo json_encode(['error'=>'Invalid email']); exit; }

    $total = 0; $orderItems = [];
    foreach ($items as $item) {
        $pid=intval($item['product_id']); $qty=intval($item['quantity']);
        if ($pid<=0||$qty<=0) { http_response_code(400); echo json_encode(['error'=>'Invalid item']); exit; }
        $stmt=$db->prepare("SELECT id,name,price,stock FROM products WHERE id=?"); $stmt->bind_param('i',$pid); $stmt->execute();
        $product=$stmt->get_result()->fetch_assoc();
        if (!$product) { http_response_code(404); echo json_encode(['error'=>"Product $pid not found"]); exit; }
        if ($product['stock']<$qty) { http_response_code(400); echo json_encode(['error'=>"Insufficient stock for {$product['name']}"]); exit; }
        $total += $product['price']*$qty;
        $orderItems[] = ['product_id'=>$pid,'quantity'=>$qty,'price'=>$product['price']];
    }

    $userId = $_SESSION['user_id'] ?? null;
    $stmt=$db->prepare("INSERT INTO orders (user_id,customer_name,customer_email,customer_phone,customer_address,total) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('issssd',$userId,$name,$email,$phone,$address,$total); $stmt->execute();
    $orderId=$stmt->insert_id;

    foreach ($orderItems as $oi) {
        $s=$db->prepare("INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)");
        $s->bind_param('iiid',$orderId,$oi['product_id'],$oi['quantity'],$oi['price']); $s->execute();
        $u=$db->prepare("UPDATE products SET stock=stock-? WHERE id=?");
        $u->bind_param('ii',$oi['quantity'],$oi['product_id']); $u->execute();
    }
    echo json_encode(['order_id'=>$orderId,'total'=>$total,'message'=>'Order placed successfully']); exit;
}
http_response_code(405); echo json_encode(['error'=>'Method not allowed']);
