# Example of using JWT on Lumen Laravel API >= 5.5

This is an example of firebase/php-jwt on Lumen Laravel API.
In this example i did't use ORM for query on Database,
i used my own SQL queries and a Relational DB, My SQL.

Programming language PHP.

# CONGRATS TO Zeeshan Ahmad

You can do this too, and my CONGRATS to Zeeshan Ahmad, very nice POST that explain me how to do this.
Link to post: https://medium.com/tech-tajawal/jwt-authentication-for-lumen-5-6-2376fd38d454

I had to change some things to use it with my SQL queries, but the POST is so GREAT!

# JWT authentication for Lumen >= 5.5 (In this example 5.5)
https://medium.com/tech-tajawal/jwt-authentication-for-lumen-5-6-2376fd38d454

# With no ORM
1. add to .env file
	
	JWT_SECRET=26THT6kp7OA1XqPgvaAt6M8X2Wkli6 // str_random(30) for generate it

2. uncomment on bootstrap/app.php

	$app->withFacades();
	$app->withEloquent();

3. install dependencie

	$ composer require firebase/php-jwt

4. Create AuthController

	<?php

	namespace App\Http\Controllers;

	use Validator;
	use App\User;
	use Firebase\JWT\JWT;
	use Illuminate\Http\Request;
	use Firebase\JWT\ExpiredException;

	class AuthController extends Controller
	{
		 /**
		  * The request instance.
		  *
		  * @var \Illuminate\Http\Request
		  */
		 private $request;

		 /**
		  * Create a new controller instance.
		  * 
		  * @param  \Illuminate\Http\Request  $request
		  *
		  * @return void
		  */
		 public function __construct(Request $request){
		     $this->request = $request;
		 }

		 /**
		  * Create a new token.
		  * 
		  * @param  Array $user Data user to store on Token
		  * @return string
		  */
		 protected function jwt($user)
		 {
		     $payload =
		     [
		         'iss' => "lumen-jwt", // Issuer of the token
		         'sub' => $user, // Subject of the token
		         'iat' => time(), // Time when JWT was issued. 
		         'exp' => time() + 60*60 // Expiration time
		     ];
		     
		     /**
		      * Return TOKEN.
		      * Here we encode using JWT_SECRET, configurated on .env file.
		      * This secret we use it to decode the Token too.
		      */
		     return JWT::encode($payload, env('JWT_SECRET'));
		 } 

		 /**
		  * Authenticate a user and return the token if the provided credentials are correct.
		  * 
		  * @param  \App\User $user Model to use his atributes and methods 
		  * @return mixed
		  */
		 public function authenticate(User $user)
		 {
		     $this->validate($this->request,
		     [
		         //'email'     => 'required|email', // This one if you are auth with email only
		         'login'     => 'required', //Use this if you are auth with a UserName
		         'pass'  => 'required'
		     ]);
		     /** Find the user on DB */
		     //$user = User::where('email', $this->request->input('email'))->first(); //This when use ORM
		     $user = User::getByLoginMail($this->request->input('login'),$this->request->input('login'));
		     /** Convert user resultset into an array */
		     $user=json_decode(json_encode($user),true);
		     if(!$user)
		     {
		         // You wil probably have some sort of helpers or whatever
		         // to make sure that you have the same response format for
		         // differents kind of responses. But let's return the 
		         // below respose for now.
		         return response()->json([
		             'id'=>-1,
		             'error' => 'Usuario no existe'
		         ], 400);
		     }
		     /** Verify the password using your prefer cypher method and generate the token.
		      * In this example we use md5(md5) 
		      * 
		     */
		     else if(hash('sha256' ,md5($this->request->input('pass')))==$user[0]['pass_user'])
		     {
		         return response()->json([
		             'id'=>1,
		             'token' => $this->jwt($user)
		         ], 200);
		     }
		     else
		     {
		         /** Bad CREDENTIALS */
		         return response()->json([
		             'id'=>-1,
		             'name' => 'Usuario o Contraseña incorrectos'
		         ], 400);
		     }

		     /**
		      * NOTE THAT ABOVE IF ELSE STATEMENTS YOU CAN MODIFY
		      * TO RESPONSE SOME MORE INFO TO CLIENT
		      */
		     
		 }
	}

5. Route for login

	/** Authenticate route */
	$router->post('/login','AuthController@authenticate');

6. Prove with PostMan if you want

	Headres on Post
	Accept:application/json
	Content-Type:application/json

	Body on Raw
	{
		"login":"Vitolo",
		"pass":"123456"
	}

7. Create the Middleware to protect our routes

	<?php
	namespace App\Http\Middleware;
	use Closure;
	use Exception;
	use App\User; // Model we are using VERY IMPORTANT TO CONFIGURE THIS IF YOU NEED
	use Firebase\JWT\JWT;
	use Firebase\JWT\ExpiredException;

	class JwtMiddleware
	{
		 public function handle($request, Closure $next, $guard = null)
		 {
		     /** Get Token with GET input */
		     $token = $request->get('token');
		     
		     if(!$token) {
		         /** Unauthorized, no token */
		         return response()->json([
		             'id'=>-1,
		             'error' => 'No hay Token.'
		         ], 401);
		     }
		     try {
		         /** Decode Token */
		         $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
		     } catch(ExpiredException $e) {
		         return response()->json([
		             'id'=>-1,
		             'error' => 'Token a expirado.'
		         ], 400);
		     } catch(Exception $e) {
		         return response()->json([
		             'id'=>-1,
		             'error' => 'Error docodificando Token.'
		         ], 400);
		     }
		     //$user = User::find($credentials->sub); //If you are using ORM and only store the ID on TOKEN
		     $user=$credentials->sub; //If you store whole user data on Token 
		     /** Put user data in the request so that you can grab it from there */
		     $request->auth = $user;
		     return $next($request);
		 }
	}

8. Add routeMiddleware on bootstrap/app.php

	$app->routeMiddleware([
    	'jwt' => App\Http\Middleware\JwtMiddleware::class,
	]);

9. Middleware group on routes to protecte them

	$router->group(['middleware' => 'jwt'],function() use ($router){
			$router->get('/users','Controller@method');
			// OTHER ROUTES
    });

10. Example using token

	http://127.0.0.1:8000/users?token=hgsgt45tdgetGTG47gj&Yh4j/ji48JY...