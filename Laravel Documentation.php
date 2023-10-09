============================================================================================================================
                                                   INSTALLATION
============================================================================================================================

1. Install Composer
2. Install Laravel
    Open command prompt and type below command to install laravel globaly.
	composer global require laravel/installer
3. Create Laravel Project
    cd desktop -> Location of Project
	composer create-project laravel/laravel project-name
4. To Run Project
    cd project-name
    php artisan serve
this will give url of project.

Understanding Working Model
Step1: Define Routing Process
Step2: Create views
Step3: Create Controller
Step4: Migration

_________________________
ROUTING 
_________________________

get    ----------> to view url
post   ----------> to store data
put    ----------> store/update
patch  ----------> store/update
delete ----------> to delete 

Open web.php

Route::get('/demo',function(){
	echo "Hello World";
});
It will directly open page with "Hello World" content displayed in it. Here we have not created any view page we defined route and inside function we described what that page should include.

Route::get('/',function(){
	return view('welcome');
});
It will search welcome page and then print it's content.

Route::get('/demo/{name},{id ?}',function($name, $id=null){
	echo $name."";
	echo $id;
});
Here id is optional as it has ? in end.
If you type /demo it will give 404 as name paramenter is required to view this page
correct url will be like /demo/abc
and screen will show abc
with /demo/abc,10    output will be abc 10.

Route::get('/demo/{name/{id ?}}',function($name, $id=null){
	$data = compact('name','id');
	return view('demo')->with($data);
});
compact is a function which is used to convert variable to array.


_________________________
VIEWS 
_________________________

Inside Views we create Blade Template
Blade Template
Template engine of Laravel (Template Engine used to control ui content easily)
Provides it's own structures like condiition statements and loops.
Creates file with extension .blade.php

Blade Template Syntax:
{{ $name }}     -> Simple echo it does not decode html.
{!! $name !!}	-> It decodes html.

Blade Conditional Directives
1. @if()     @endif
   @if()     @else     @endif
   @if()     @elseif     @else     @endif
2. @unless     @endunless
3. @isset($name)
      {{ name }}
	@endisset
4. @for     @endfor
5. @while     @endwhile
6. @continue     @break
7. @foreach @endforeach
$arr = [1,2,3,4,5];
<select>
@foreach($arr as $key => $ar)
<option value="{{key}}">
	{{$ar}}
</option>
@endforeach
</select>

Standard of creating blade file
Create layout folder inside Views
views/layout/
Inside layout folder create 3 files:

1. main.blade.php
@include('layout.header')
<div class='container'> @yield('main-section') </div>
@include('layout.footer')
2. header.blade.php
3. footer.blade.php

Then in Views create pages all these pages will be displayed by main.blade.php file 
Example of home page
views/home.blade.php
@extends('layout.main')
@section('main-section')
--home page contents--
@endsection

To make title dynamic for each page in header file
@stack('title')
and in home file 
@push('title')
<title> Homepage Title </title>
@endpush

Note:
1. Directive syntax -> @ starts with @
2. Creating Reusable content
@section('contact-number')
reusable-content
@endsection

now this content can be access anywhere in the page using 
@yield('contact-number')
3. extends("default")
By this way we link page with pages
4. To declare url in href
<a href="{{url('contact')}}">Contact</a>

_________________________
CONTROLLER 
_________________________

Class based php file that contains functions and it's member
Types of controllers
1. Basic Controller
syntax:  php artisan make:controller ControllerName
example: php artisan make:controller BasicController

2. Single Action Controller 
syntax:  php artisan make:controller ControllerName --invokable
example: php artisan make:controller SingleFunctionController --invokable

3.Resource Controller 
syntax:  php artisan make:controller ControllerName --resource
example: php artisan make:controller ResourceController --resource
 
 Inside web.php file define route of page using Controller 
 use App\Http\Controllers\BasicController;
 use App\Http\Controllers\SingleFunctionController;
 use App\Http\Controllers\ResourceController;
 
 Route::get('/',[BasicController::class,'index']);
 Route::get('/courses',BasicController::class);
 Route::resource('/about',ResourceController::class);
 
 Actions Handles by Resource Controller
 
 Method   |  URI                 | Action  | Route name
 GET      | /photos              | index   | photos.index
 GET      | /photos/create       | create  | photos.create
 POST     | /photos              | store   | photos.store
 GET      | /photos/{photo}      | show    | photos.show
 GET      | /photos/{photo}/edit | edit    | photos.edit
 PUT|PATCH| /photos/{photo}      | update  | photos.update
 DELETE   | /photos/{photo}      | destroy | photos.destroy
 
 
__________________________
MIGRATION 
__________________________

Creating table
Table name should incude 's' at end.
php artisan make:migration create_customers_table

Table Columns
$table->id('customer_id);
$table->string('name',60);
$table->date('dob')->nullable;
$table->enum('gender',["M","F","O"])->nullable;
$table->string('password');
$table->integer('points')->default(0);
$table->timestamps();

Default columns id, created_at, updated_at. timestamps() provides two columns named created_at, updated_at.

php artisan migrate           (to run migration)
php artisan migrate:rollback  (to undo last migration)
php artisan migrate:refresh   (to refresh database table, it first delete all tables and then add them again)

To add columns in existing table
php artisan make:migration add_columns_to_customers_table
$table->string('country',50)->nullable()->after('adress');
$table->string('state',50)->nullable()->after('adress');
php artisan migrate


__________________________
MODEL
__________________________

Model is created with same table name without 's' at end and with first letter capital
customers -> Customer
php arisan make:model Customer
 In model file we need to define two things
 1. Table name
 2. Primary Column
Class Customer extends Model
{
	use HasFactory;
	protected $table = "customers";
	protected $primaryKey = "customer_id";
} 

$customers = Customer::all();
this variable now contains all data of customers table.
Note: while using this query it should keep in mind that relavent Model should be addded at the top of page.
use App\Models\Customer;

To create migration and Model at one time.
php artisan make:model Product --migraton  


__________________________
FORM
__________________________

Important Points:
1. @csrf is required to submit form.
2. in the action field type action="{{url('/')/register}}"
3. to show inserted values in controller file
   public register(Request $req)
   {
	print_r($req->all());
   }
4. to validate form 
   $req => validate([
   'name' => 'required',
   'email' => 'required|email',
   'password' => 'required',
   'confirm' => 'required|same:password',
   ]);
5. to show recent inserted data in the fields after non successfull submission of form 
   value="{{old('name')}}"
6. to show error with each field
   <span class="text-danger">
   @error('name')
   {{$message}}
   @enderror
   
   
__________________________
COMPONENT
__________________________

php artisan make:component Input

Two files will be created automatically
input.php
input.blade.php

Inside input.blade.php
type general form group code
<div class="form-group">
<label id="{{$name}}"> {{$label}} </label>
<input type="{{$name}}" class="form-control" />
</div>

this will be called as 
<x-input type="text" id="name" name="name" label="Please enter your name" /> in input.php

Inside class declare variables
Public $name;
Public $type;
Public $labe;

In construct function pass the variables
_Construct()
{
	$this->name = $name;
	$this->type = $type;
	$this->label = $label;
}


__________________________
CRUD
__________________________

In controller file

Create:
Public function create(){
	$url = "url('/customer/create')";
	$title = "New Registration";
	$data = compact('url','title');
	return view('layout.customerform')->with($data);
}

Save:
public function store(Request $req){
	$req => validate([
   'name' => 'required',
   'email' => 'required|email',
   'password' => 'required',
   'confirm' => 'required|same:password',
   'gen' => ['required','in:M,F,O']
   ]);
   
   $customer = new Customer;
   $customer->name = $req['name'];
              |             |
            column-name   field-name
   $customer->password = md5($req['password']);
   -----
   $customer->save();
   return redirect('/customer');			 
}

Edit:
public function edit($id){
	$customer = Customer::find($id);
	
	if(is_null($customer)){
		return redirect('/customer');
	}
	else{
		$url = url('/customer/update')."/".$id;
		$title = "Update Registration";
		$data = compact('customer','url','title');
		return view('layout.customerform')->with($data);
	}
}

Update:
public function update($id, Request $req){
	$customer = Customer::find($id);
	$customer->name = $req['name'];
	--- except password
	$customer->save();
	return redirect('/customer');
}

Delete:
public function delete($id){
	$customer = Customer::find($id);
	if(!is_null($customer)){
		$customer->delete();
	}
	return redirect('/customer');
	
	
	
}



__________________________
NAMED ROUTE
__________________________

We can call the route in two ways:

if Route is defined like this
Route::get('/customer/delete/{$id}', [CustomerController::class,'delete']);
then it will be called as:
href="{{url('/customer/delete')}}/{{$customer->customer_id}}"

Named route
if Route is defined like this 
Route::get('/customer/edit/{id}', [CustomerController::class,'edit'])->name('customer.edit');
then it will be called as:
href="{{route('customer.edit',['id'=>$customer->customer_id])}}"


__________________________
HELPER FILE
__________________________


Create helper.php file in app. 
This file is usefull to run any function through out the website.
It is used to create repetative code that will be called from any part of website.
After creating helper.php file we need to declare it's path in composer.json file.

inside autoload
"files" : ["app/helper.php"],

function inside helper.php
if(!function_exists('generate_date_formate')){
	function generate_date_formate($date, $format){
		return date($format, strtotime($date));
	}
}


__________________________
MUTATOR & ACCESSOR
__________________________

Mutator & Accessor modifies the data at the time of insertion and retriving from database as well.
Mutator ----> While Inserting 
Accessor ----> While Accessing data 

In Module file 
public function setNameAttribute($value){
	$this -> attributes['name'] = ucwords($value);
}
After using this function Name field data will be stored in database in Capitalise format.

public function getStateAttribute($value){
	$this -> attributes['state'] = ucwords($value);
}

naming function: This function name includes name of Column
In case column name is like user_name then
getUserNameAttribute()
setUsernameattribute()


__________________________
SESSIONS
__________________________


By default Laravel sets some sessions.
To view those use
All-> session()->all();
specific-> session('id');

To set 
$Request->session()->put('name','Priyanka');
It will stay untill destroy function for this will execute OR upto time described in config file for session.

For temporary session
use flash
$Request->session()->flash('pwd','321');

It will be automatically deleted as and when another page is accessed.

To destroy
session()->forget('name');

Note: Remember while using $Request below code should be added at the top of page
use Illuminate\Http\Request;

Example: 
Route::get('/set-session', function(Request $req){
	$req->session()->put('id','123');
});


__________________________
SOFT DELETE
__________________________

It means to trash the data instead of deleting it permanently from database.

In modal page add:
use Illuminate\Database\Eloquent\SoftDeletes;

Inside class{
	use Hasfactory; 
    use SoftDeletes;
	withTrashed();
	onlyTrashed();
	forcedelete();
	restore();
}

Then add new column in the table by using following command
php artisan make:migration add_deleted_at_to_customers_table

inside alter table file add
$table->softDeletes(); in up()
$table->dropSoftDeletes; in down()

View: $customers = Customer:: onlyTrashed()->get();
Delete: $customers = Customer:: withTrashed()->find($id);
        $customers ->forcedelete();
Restore: $customers = Customer:: withTrashed()->find($id);
         $customer->restore();  
		 
		 
__________________________
LARAVEL/COLLECTIVE HTML FORM
__________________________


{!! Form::open([
    'url' => url()->current(),
	'method' => 'post',
	'id' => 'contact',
	'role' => 'form',
	'class' => 'contact_form',
	'enctype' => 'multipart/form-data'
]) !!}

{!! Form::text('author','',[
    'class' => 'required input-field',
	'id' => 'author',
	'maxlength' => '40'
])!!}

{!! Form::email(' ', ' ', [ ])!!}
                 |    |    |
				 name |    |
				     value |
					       all other things
						   
{!! Form::textarea('','',[])!!}

	{!! Form::select('slct',[
	'1' => 'India',
	'2' => 'America',
	'3' => 'Russia'
	],'3',[
	'class' => 'required',
	'id' => 'slct'
	])!!}
	
{!! Form::radio('rdo','Male')!!}
{!! Form::radio('rdo','Female')!!}
{!! Form::radio('rdo','Other')!!}

{!! Form::checkbox('chk','first option')!!}
{!! Form::checkbox('chk','second option')!!}

{!! Form::number('name','value')!!}


When Uploading a FILE

{!! Form::open([
    'url' => url('/upload'),
	'files' => true,
	'method' => 'post',
	'id' => 'contact',
	'role' => 'form',
	'class' => 'contact_form',
	'enctype' => 'multipart/form-data'
]) !!}

{!! Form::file('image')!!}
{!! Form::submit('submit')!!}

In controller file 
public function upload(Request $req){
        $author = $req['author'];
        $email = $req['email'];
        $subject = $req['subject'];
        $msg = $req['text'];
        $country = $req['slct'];
        $gender = $req['rdo'];

        $req->file('image')->store('public/uploads');

         $data = compact('fileName','email','author','subject','msg','country','gender');
         
    return view('upload')->with($data);
    }

Note: Laravel by default stored file with unique name to store file with your own name 
$fileName = time()."-DefineName.".$req->file('image')->getClientOriginalExtension();
$req->file('image')->storeAs('public/uploads',$fileName);


_________________________
SEEDER & FAKER
_________________________

It is used to add large amount of data for the purpose of testing.
Use command:
php artisan make:seeder customerseeder

After this you will find seeder folder inside database folder.
In database file inside run add following code
$this->call([
customerseeder::class
]);

In customerseeder file 
use App\Models\Customer;
use Faker\Factory as Faker;

public function run(){
	$faker = Faker::create();
	for($i=1; $i<=20; $i++){
		$customer = new Customer;
		$customer->name = $faker->name;
		----
		$customer->save();
	}
}
For insserting value run below command
php artisan db:seed


_________________________
SEARCH & PAGINATION
_________________________


Search bar in customer view page

public function view(Request $req){
	$search = $req['search']?$req['search']:'';
	if($search != ''){
		$customers = Customer::where('name','LIKE','%$search%')->orwhere('email','LIKE','%search%')->get();
	}
	else{
		$customers = Customer::paginate(20);
	}
	$data = compact('customers','search');
	return view('customer-view')->with($data);
}

In Customer-view page to add pagination buttons
{{$customers->links()}}

In search input value="{{ $search }}"


_________________________
LARAVEL LOCALIZATION
_________________________


To set language it is done from lang folder by default en folder exists. Paste below code in lang.php file in en
<?php
return[
'welcome' => "Welcome to our Laravel webpage"
];
?>

Create as many folder as per languages you want
hen add links in home page like 
English (href="/") Hindi (href="/hi") Russia (href="/rus")
In web.php
Route::get('/{lang?}', function($lang = null){
	App:: setLocale($lang);
	return view('home');
});

_________________________
ROUTE GROUPING
_________________________


Routes who's prefix are common can be put together in one function
Route::group(['prefix' => '/customer'], function(){
	Route::get('create',[CustomerController::class,'create']);
	---
});

Route then hade /customer will be written as
Route::get('/')....

_________________________
STUB
_________________________


Stub is used to customize controller/models files.
By default stub folder is not published to make it publish write below command 
php artisan stub:publish

It will display stub folder from this you will find controller.plain.stub file 
Thi sfile is metadata for Basic Controller file.

If you want index function should appear at time of basicController file is created then make changes in controller.plain.stub file.
After this every time a Basic controller created index function will appear automatically.

_________________________
FOREIGN KEY
_________________________


To create a foreign key you need two tables as it's purpose is to establish connection between two tables on the basis of common column.
Example:
              Members                 Groups
			  id          /->         group_id
			  name       /            name
			  email     /             description
			  group_id /
			  
Create table groups first and then Members
while creating members table add below Columns
$table->unsignedBigInteger('group_id');
$table->foreign('group_id')->references->('group_id')->on('groups');

Now members table group_id will have only those values that groups table group_id has 
You will not be able to make entry in members table without choosing any one from the list 

one to  one
In Member.php Models file 
use Has Factory;
protected $primaryKey = "member_id";

function getGroup(){
	return $this->hasOne('App\Models\Group', 'group_id');
}

In controller file 
return Member::find(1)->getGroup;

To display Member & group together
return Member::with('getGroup')->get();

one to many
Members.php 
function group(){
	return $this->hasMany('App\Models\Group','group_id',"group_id");
}
Controller file 
return Member::with('group')->get();

_________________________
MIDDLEWARE
_________________________

Route MIDDLEWARE
Kind of intermediator which will filter the requests.
php artisan make:middleware WebGuard

In WebGuard file inside handle function 
if(session()->has('user_id))
	return $next($request);
elsereturn redirect('no-access');
	
	Route::get('/no-access',function(){
		echo "You are forbidden to access this page";
		die;
	});
	
Kernel.php
protected $routeMiddleware = [
'guard' => \App\Http\Middleware\WebGuard::class
]
php artisan config:cache

After this in web.php file do following code with the pages route that need Middleware 
Route::get('/data',[IndexController::class,'index'])->middleware('guard');


Group Middleware
Web.php
Route::middleware(['guard', 'web'])->group(function(){
	Route::get('/data',[IndexController::class,'index']);
	-----
});
Process
Let us assume one can visit page if his age>18 and is logged in.

Create two middleware LoggedinCheck and AgeCheck
In kernel.php file inside $middlewareGroups
create new group 
'guard' = [
\App\Http\Middleware\AgeCheck::class,
\App\Http\Middleware\LoggedinCheck::class,
]
then add web.php code.


Global Midddleware
Create a middleware to make a middleware globally applicable to every page youneed to get path of your middleware 
\App\Http\Middleware\WebGuard::class 

php artisan config:cache 


_________________________
MODEL BINDING
_________________________

inside web.php
Route::get('/many/{id}',[IndexController::class,'more']);
In Controller file 
Public function more(Member $id){
	return $id;
}
It will display data for particular id only.


_________________________
IMPORTANT POINTS
_________________________

1. Get id of latest inserted record after $customer->save();
$customer->id = $request['id'];

2. To get Latest record
$customer = Customer::latest()->first();
$data = compact('customer');
return view('customer-view')->with($data);

3. Sometimes Localhost shows error while migrate query run
Solution:
APP\Providers->appserviceprovider.php
use Illuminate\Support\Facades\Schema;
Public function boot(){
	Schema::defaultStringLength(191);
} 


_________________________
NOTIFICATION/EMAIL
_________________________

Create account in mailtrap.io
Click on start testing, it will give credentials that will be used in .env file to send email.

Create Notificaton file 
php artisan make:notification welcomeNotification
then add dummy values in users table using seeder and faker.
 To create jobs table in database that will have sent notification details write:
 implements ShouldQueue after
class welcomeNotification extends Notification implements ShouldQueue 
php artisan queue:table 
Inside .env file replace sync with database
QUEUE_CONNECTION = database 
Then run you will find email notification inside mailtrap account  

In controller file 
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;

$user = User :: first();
Notification :: send($user,new WelcomeNotification);

For email
In controller file 
use App\Mail;
use Illuminate\Support\Facades\Mail;

Mail::to('priyanka@leapoffaithtech.com')->send(new ContactNotification($name)); 



============================================================================================================================
                                                   SHOPIFY API
============================================================================================================================


To Start with GraphQL API
1. Create Shopify account
2. Create new app
3. Do configuration settings for REST as well as for GraphQL configuration
4. After this Copy Token from REST API  credentials and save it.

5. Open Postman
6. Paste url 
https://{store-name}.myshopify.com/admin/api/2023-07/graphql.json
replace {store-name} with the name of your store
7. In headers add X-Shopify-Access-Token and it's value (Token that you copied before)
8. To run your query add your query in body->GraphQL  


GraphQL Queries can be generated by using GraphQL Explorer

GraphQL Queries
To get Simple Products


{
    products (first: 10) {
      edges {
        node {
          id
          title
          description
        }
      }
    }
  }
  
  
  
  
  ============================================================================================================================
  To get Products with variation
  
  
  {
    products (first: 20) {
      edges {
        node {
           id
            descriptionHtml
			description
            title
            createdAt
            handle
            status
            options(first:10){
                name
                values
            }
variants(first:10){
    edges{
        node{
 id
    sku
    compareAtPrice
    price
        }
    }
    }
        }
      }
    }
  }


  ============================================================================================================================
  
  To Create new Product in store
  
  
  mutation MyMutation {
  productCreate(
    input: {
	descriptionHtml: "Variant Product Dummy", 
	status: ACTIVE,
	title: "Variant Skirt", 
	options: ["Size","Color"],
	variants: [{compareAtPrice: "700", price: "500", options: ["Medium","Red"], sku: "Variant Skirt M"},
	{compareAtPrice: "700", price: "500", options: ["Medium","Blue"], sku: "Variant Skirt M"},
	{compareAtPrice: "700", price: "500", options: ["Medium","Green"], sku: "Variant Skirt M"},
	{compareAtPrice: "700", price: "500", options: ["Large","Red"], sku: "Variant Skirt L"},
	{compareAtPrice: "700", price: "500", options: ["Large","Yellow"], sku: "Variant Skirt L"},
	{compareAtPrice: "700", price: "500", options: ["Large","Green"], sku: "Variant Skirt L"}
	{compareAtPrice: "400", price: "350", options: ["Small","Black"], sku: "Variant Skirt S"}]}
  ) {
    product {         
            id
            descriptionHtml
            title
            createdAt
            handle
            status
            options(first:10){
                name
                values
            }
variants(first:10){
    edges{
        node{
 id
    sku
    compareAtPrice
    price
        }
    }
    }
       }
  }
}
