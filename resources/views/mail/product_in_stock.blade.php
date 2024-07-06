<!DOCTYPE html>
<html>
<head>
    <title>Product In Stock</title>
</head>
<body>
    <p>Dear {{$customerName}},</p>
    <p>The product {{$product->title}} is currently again in stock.</p>

    <a href="http://localhost:3000/products/{{$product->id}}">Click here for details.</a>
</body>
</html>
