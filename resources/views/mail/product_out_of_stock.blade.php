<!DOCTYPE html>
<html>
<head>
    <title>Product Out of Stock</title>
</head>
<body>
    <p>Dear {{$customerName}},</p>
    <p>The product {{$product->title}} is currently out of stock.</p>
    <p>You can subscribe to be notified when it's back in stock.</p>

    <a href="http://localhost:3000/products/{{$product->id}}">Click here to subscribe to product</a>
</body>
</html>
