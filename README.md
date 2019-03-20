<p id="hello-world-screen"><img src="https://www.pakjiddat.pk/pakjiddat/ui/images/hello-world.png" alt="Pak Php Framework Installation"/></p>

<h3>Introduction</h3>
<p>The Pak Php Framework is a framework for developing Php applications. It can be used to develop command line applications as well as browser applications based on <a href='https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller'>Model View Controller (MVC) design pattern</a>. The following applications have been developed using the Pak Php Framework: <a href='https://www.pakjiddat.pk/'>Pak Jiddat website</a>, <a href='https://islamcompanion.pakjiddat.pk/'>Islam Companion website</a></p>

<p>An application framework provides libraries and tools for developing applications. The Pak Php framework provides error handling, function validation, log handling, session handling, url request handling based on MVC design pattern, url routing, translation and testing. It also includes utility classes that provide commonly used features such as Template Engine, Database Abstraction, Error Handling, File and Folder management and more.</p>

<p>The utility classes and features provided by the Pak Php Framework are implemented as separate components that are easy to extend. All components of the Pak Php Framework are based on the principles of <a href='https://en.wikipedia.org/wiki/Separation_of_concerns'>Separation of concerns</a> and <a href='https://en.wikipedia.org/wiki/Don%27t_repeat_yourself'>Don't repeat yourself</a>.</p>

<p>Third party frontend libraries such as <a href='https://getbootstrap.com/'>Twitter Bootstrap</a>, <a href='https://jquery.com/'>JQuery</a>, <a href='https://www.w3schools.com/w3css/'>W3.CSS</a> etc can be integrated with the Pak Php Framework. The websites <a href='https://www.pakjiddat.pk/'>Pak Jiddat</a> and <a href='https://islamcompanion.pakjiddat.pk/'>Islam Companion</a> were developed using the Pak Php Framework.</p>

<h3>Requirements</h3>
<p>The Pak Php Framework requires Php 7.2 and above. The code for the Pak Php Framework is fully commented and compliant with the <a href='https://www.php-fig.org/psr/psr-2/'>PSR-2</a> coding guidelines. Parameter and return types are given for all methods</p>

<p>The Pak Php Framework does not have any external dependencies apart from the XDebug Php extension. It has its own Template Engine and Test Manager. It provides a simple framework for developing well tested applications.</p>

<h3>Development of the website</h3>
<p>The website was developed by following the <a href='http://theleanstartup.com/principles'>Lean Startup principles</a>. The book <a href='https://www.packtpub.com/business/understanding-software'>Understanding Software</a> was an invaluable guide in the development of the framework.</p>

<p>The goal of the Pak Php Framework is to provide a light weight set of tools for developing professional, well tested web applications. Applications developed using the Pak Php Framework should be based on Lean Startup principles.</p>

<h3>Application Structure</h3>
<p>A single instance of the Pak Php Framework can support several applications. Each application must be in its own folder. The following shows the structure of a sample Pak Php Framework application:</p>

<span data-toggle="collapse" data-target="#example1" class="cursor-pointer"><b><u>Click to view structure</u></b></span>

<pre id="example1" class="collapse"><b>
├── config
│   ├── Custom.php
│   ├── General.php
│   ├── Path.php
│   ├── RequiredObjects.php
│   ├── Test.php
│   └── UrlMapping.txt
├── Config.php
├── lib
├── test
│   └── results
│       ├── codecoverage
│       └── tracelogs
└── ui
    ├── css
    ├── html
    ├── images
    ├── js
    ├── pages
</b></pre>

<p class="mt-4">The config folder holds the application configuration files. Each file should contain a single method called <b>GetConfig</b>. This method should return an array containing configuration data, which overrides the default framework configuration. See the folder <b>framework/config</b>, for the default configuration files.</p>

<p>The <b>Custom.php</b> file can contain custom configuration. The <b>UrlMapping.txt</b> file defines the url routing information for the application.</p>

<p>The <b>lib</b> folder should contain the user defined library files. The <b>ui</b> folder contains the user interface related code. It should contains the sub folders shown in the above example. The <b>css</b> sub folder contains application css files, the <b>images</b> folder contains application image files, the <b>js</b> folder contains application JavaScript files. The <b>html</b> folder contains the html template fles. The <b>pages</b> folder should hold files that derive from the <b>\Framework\Application\Page</b> base class.</p>

<h3>Framework Structure</h3>
<p>The Pak Php Framework has the following structure:</p>

<span data-toggle="collapse" data-target="#example2" class="cursor-pointer"><b><u>Click to view structure</u></b></span>

<pre id="example2" class="collapse"><b>
├── autoload.php
├── index.php
├── .htaccess
├── framework
│   ├── application
│   │   ├── Api.php
│   │   ├── Application.php
│   │   ├── CommandLine.php
│   │   ├── libraries
│   │   │   ├── ErrorHandling.php
│   │   │   ├── FunctionValidation.php
│   │   │   ├── LogHandling.php
│   │   │   ├── RequestHandling.php
│   │   │   ├── SessionHandling.php
│   │   │   ├── Translation.php
│   │   │   └── UrlRouting.php
│   │   ├── Page.php
│   │   └── Web.php
│   ├── config
│   │   ├── base
│   │   │   ├── GeneralConfig.php
│   │   │   ├── PathConfig.php
│   │   │   ├── RequiredObjectsConfig.php
│   │   │   └── TestConfig.php
│   │   ├── Config.php
│   │   ├── Initializer.php
│   │   └── Manager.php
│   ├── documentation
│   │   └── changelog.txt
│   ├── templateengine
│   │   ├── BasePage.php
│   │   ├── Generator.php
│   │   └── HeaderTags.php
│   ├── testmanager
│   │   ├── BlackBoxTesting.php
│   │   ├── CodeCoverageGenerator.php
│   │   ├── TestDataManager.php
│   │   ├── TestFunctionProcessor.php
│   │   ├── TestFunctionValidator.php
│   │   ├── TestResultsManager.php
│   │   ├── UiTesting.php
│   │   ├── UnitTestRunner.php
│   │   └── WhiteBoxTesting.php
│   ├── utilities
│   │   ├── Authentication.php
│   │   ├── autoload.php
│   │   ├── CacheManager.php
│   │   ├── commentmanager
│   │   ├── databasemanager
│   │   ├── documentation
│   │   │   └── changelog.txt
│   │   ├── Email.php
│   │   ├── Encryption.php
│   │   ├── errormanager
│   │   ├── examples
│   │   ├── Excel.php
│   │   ├── filesystem
│   │   ├── LogManager.php
│   │   ├── Profiler.php
│   │   ├── StringUtils.php
│   │   ├── TemplateUtils.php
│   │   └── UtilitiesFramework.php
│   └── vendors
</b></pre>

<h5>Main entry point</h5>
<p>The <b>index.php</b> file is the main entry point for the application. All url requests are routed by the <b>.htaccess</b> file to the <b>index.php</b> file. The <b>autoload.php</b> file is used to autoload classes. All classes should have a namespace. The folder structure of a class should match the class namespace</p>

<h5>Application base classes</h5>
<p>All Pak Php Framework applications are child classes of the <b>Application</b> base class, which is <b>application/Application.php</b>. Currently three types of applications are supports. Api, Command Line and Web.</p>

<p>Api applications extend the <b>Api</b> base class. Command Line applications extend the <b>CommandLine</b> base class. Web applications can extend either the Web class or the Page class. The Page class is derived from the Web class.</p>

<p>The main framework features which are Url Routing, Session Handling, Error Handling, Translation, Function Validation, Log Handling and Requestion Handling are implemented by classes in the <b>application/libraries</b> folder. Each feature is implemented separately from other features</p>

<h5>Configuration</h5>
<p>Configuration is one of the main features of the Pak Php Framework. It allows class objects to be used without explicitly initializing the object. The user only has to mention the class once in <b>config/RequiredObjects.php</b>. For example:</p>

<pre><b>$config['contactpage']['class_name'] = '\PakJiddat\Ui\Pages\Contact';</b></pre>

<p>The class can then be used using the syntax: <b>Config::GetComponent("contactpage");</b>. The framework takes care of initializing the class object. If the object has already been created, then it is simply returned. All classes are autoloaded using <a href='https://www.php-fig.org/psr/psr-4/'>PSR-4 autoloading standard</a>.</p>

<p>Application data is stored in configuration files inside the folder: <b>config/</b>. See the <a href='#application-structure'>user application structure</a> for details. Configuration variables can be accessed using the syntax:</p>

<pre><b>Config::$config["general"]["dev_mode"]</b></pre>

<p>The above code returns the current development mode of the application. If it is false, then application is in production mode</p>

<h5>Url Routing</h5>
<p>The Pak Php Framework provides Url Routing. The user application should contain the file <b>config/UrlMapping.txt</b>. This file specifies how the url requests will be served. For each url there is an entry. The first line in the entry is a regular expression that defines which urls to handle. The next line defines the callback function that should handle the url request. The line after that defines an optional validator callback. This is the callback to be used for validating application parameters</p>

<p>The following code shows sample contents of the <b>UrlMapping.txt</b> file:</p>

<span data-toggle="collapse" data-target="#example3" class="cursor-pointer"><b><u>Click to view</u></b></span>

<pre id="example3" class="collapse"><b>
url: ^/$
callback: {"object": "homepage", "function": "Generate"}

url: ^/articles(/view/\d+/[A-Za-z\-0-9\._%]+)?$
callback: {"object": "viewarticlepage", "function": "Generate"}

url: ^/articles/(tag|search)/[a-z\-0-9A-Z\._%]+?$
callback: {"object": "listarticlepage", "function": "Generate"}

url: ^/contact/form$
callback: {"object": "contactpage", "function": "Generate"}

url: ^/contact/add$
callback: {"object": "contactpage", "function": "SendContactMessage"}

url: ^/comment/add$
callback: {"object": "commentspage", "function": "AddComment"}
</b></pre>

<h5 class="mt-4">Template Engine</h5>
<p>The Pak Php Framework includes a template engine which allows user applications to merge application data with html templates</p>

<h5>Test Manager</h5>
<p>The Pak Php Framework provides classes for testing code. Three types of tests are supported. <a href='https://en.wikipedia.org/wiki/White-box_testing'>White Box tests</a>, <a href='https://en.wikipedia.org/wiki/Black-box_testing'>Black Box tests</a> and Ui (user interface) tests</p>

<p>White Box tests are written like <a href='https://en.wikipedia.org/wiki/PHPUnit'>PhpUnit</a> tests. The user defines test methods in a file. Each method name should start with "Test". The test is then run from the command line. Assert statements may be used within the test methods to validate conditions.</p>

<p>Black Box tests are written by defining test data inside test files. Each method has its own test data file. The first line in the test data file gives the list of parameters separated by '|'. The last three entries in the first line gives the expected return value, the type of the return value and the rule used to validate the return value. The test data file can be auto generated for each method defined by the application. The command:</p>

<pre><b>php index.php --application="[app-name]" --action="Generate Test Data"</b></pre>

<p>will generate test data files for each user defined method. The test data files will be placed in the folder: <b>test/testdata/{class-name}</b>. The name of the file is same as the name of the method. Following is a sample structure of a test data file:</p>

<pre><b>
param_int_name1|param_int_name2|param_int_name3|return_name1|return_type|rule
1|1|7|{"count":7}|array|count
</b></pre>

<p>A Ui (user interface) test allows the html of the response to be checked for errors. The <a href='https://validator.nu/'>validator.nu</a> service is used to check if the html conforms with the HTML5 standard. Broken links are also checked as part of the Ui test. The test data for the Ui test is defined in a database table. See the structure of the table: <b>pakphp_test_data</b>. It has two main fields which are the url and url parameters. This data may be auto generated by setting the <b>save_ui_test_data</b> variable to <b>true</b> in <b>config/Test.php</b>. When this option is set to true, the framework saves the current url and parameters to database</p>

<p>After a black box or white box test has been run, code coverage information for the test is displayed on the console and also saved to database. A summary of the test results is saved to database and file. A trace log of all function calls is also saved. The code coverage and function trace are generated using XDebug. The following screenshot shows the test results that are printed to the console after running black box tests:</p>

<p><img src="/pakjiddat/ui/images/black-box-test-results.png" alt="Black Box Testing Results" /></p>

<p>The following screenshot shows the code coverage summary after running black box tests</p>

<p><img src="/pakjiddat/ui/images/code-coverage.png" alt="Code Coverage Results" /></p>

<h5>Utilities</h5>
<p>The utilities folder contains classes that provide utility functions. These classes are used by the framework and may be used in user applications. See the <a href='/articles/view/257/utilities-framework'>Utilities Framework</a> package for information on how to use the utility classes.</p>

<h5>Vendors</h5>
<p>The vendors folder contains third party libraries such as <a href='https://getbootstrap.com/'>Twitter Bootstrap</a>, <a href='https://jquery.com/'>JQuery</a>, <a href='https://www.w3schools.com/w3css/'>W3.CSS</a> and <a href='https://qunitjs.com/'>QUnit</a>. These frontend libraries can be used in html templates. Reusable widgets may be developed that allow developers to easily create attractive looking applications</p>

<h3>Installation</h3>

<ul>
<li>Run the command: <b>composer require nadirlc/pak-php-framework</b> (Installation using Composer) <b>OR</b></li>
<li>Run the command: <b>git clone https://github.com/nadirlc/pak-php-framework.git</b> (Download from <a href='https://github.com/nadirlc/pak-php-framework'>GitHub Repository</a>)</li>
</ul>

<p>After the source code has been downloaded, create a MySQL database called <b>pakjiddat_pakphp</b> and import the contents of the file <b>framework/data/pak-php-framework.sql</b> to the database. This will create the database tables used by the Pak Php Framework. These tables are used for saving error data, access logs, test data and test results</p>

<h3>Examples</h3>
<p>The <b>helloworld</b> sample application shows how to get started with the Pak Php Framework. To run the application, enter the name of the host used to access the application in the <b>helloworld/Config.php</b> file. Replace <b>example.pakjiddat.pk</b> with <b>your-host-name</b>. Also enter the database server credentials in <b>helloworld/config/RequiredObjects.php</b>. After that open the application in the browser. You should see <a href='#hello-world-screen'>this screen</a></p>

<p>For a more complex example you can download and install one of the following:</p>

<ul>
  <li><a href='https://github.com/nadirlc/developers-site'>Developers Site</a>. It is a simple website that allows web developers to publish their work</li>
  <li><a href='https://github.com/nadirlc/quran-hadith-website'>Quran Hadith Website</a>. It allows users to read the Holy Quran and Hadith. Also allows subscribing to Holy Quran and Hadith by email</li>
  <li><a href='https://github.com/nadirlc/quran-hadith-api'>Quran Hadith API</a>. It provides a RESETFul API for fetching Holy Quran and Hadith data</li>
</ul>
