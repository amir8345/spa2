<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <div id="app">
       <nav>
           <router-link to="/">home</router-link>
           <router-link to="/books">books</router-link>
           <router-link to="/login">login</router-link>
       </nav>

        <router-view></router-view>


    </div>


    <script src="/js/app.js" ></script>
</body>
</html>