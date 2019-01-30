# Using PHP Slim Framework

Use this skeleton application to quickly setup and start working on a new Slim Framework 3 application. This application uses the latest Slim 3 with the PHP-View template renderer. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Test the api using postman or any rest client 
### Sample Test senerio 
#### Method:POST
#### URL: http://localhost:[port]/userLogin
#### Payload:{
 "email":"segelu@gmail.com",
 "Password":"ckondo stores"
}
#### sample response: 
{
    "email": "segelu@gmail.com",
    "Password": "ckondo stores",
    "current user": "19",
    "response status": "login success"
}

#### Method:POST
#### URL: http://localhost:[port]/createUser
#### Payload:{"name":"falugba",
 "email":"tunde@gmail.com",
 "password":"ckondo stores"
}
#### sample response: 
{
    "name": "falugba",
    "email": "tunde@gmail.com",
    "password": "19c8c7cc8068f929931fd1c65081bf66095b66dc846414abe4bdcae6988f2e20",
    "id": "20",
    "response status": "User Created Successfully"
}

#### Method:PUT
#### URL: http://localhost:[port]/changeStatusParcelDeliveryOrder/4
#### Payload:{

"status":"delivered"
}


#### sample response: 
{
    "status": "delivered",
    "id": "4",
    "response status": "Updated Successfully"
}
#### Method:GET
#### URL: http://localhost:[port]/deliveryDetails/4
#### sample response: 
{
    "Placed_by": "tolu",
    "Weight": "78kg",
    "Date delivered": "2019-01-10",
    "Status": "delivered",
    "Address": "lagos"
}
#### Method:POST
#### URL: http://localhost:[PORT]/createParcelDeliveryOrder
#### Payload:
{
"Placed_by":"tolu",
"Weight":"78kg",
"Sent_on":"2019-01-10",
"Delivered_on":"2019-01-10",
"Status":"Delivered",
"From_address":"lagos",
"To_address":"ibadan"
}



#### sample response: 
{
    "Placed_by": "tolu",
    "Weight": "78kg",
    "Sent_on": "2019-01-10",
    "Delivered_on": "2019-01-10",
    "Status": "Delivered",
    "From_address": "lagos",
    "To_address": "ibadan",
    "id": "5",
    "response status": "Delivery Order Created Successfully"
}

#### Method:PUT
#### URL: http://localhost:[PORT]/cancelParcelDeliveryOrder/1
#### Payload:
{
	"cancel":"TRUE"
}



#### sample response: 
{
    "cancel": "TRUE",
    "id": "1",
    "response status": "Updated Successfully"
}
