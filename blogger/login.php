<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Blog Admin Dash</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/tailwind.output.css" />
    <link rel="stylesheet" href="assets/css/custom.css" />
    <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
      defer
    ></script>
    <script src="../assets/js/init-alpine.js"></script>
  </head>
  <body>
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
      <div
        class="flex-1 h-full mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800"
        id="redesign"
      >
        <div class="flex items-center justify-center p-6 sm:p-12 md:w-full">
          <div class="w-full">
            <h1
              class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Login
            </h1>
            <hr class="my-8" />
            <form action="" method="post">
              <div class="form-group">
                <div class="form-element">
                  <label for="username">Username</label>
                </div>
                <div class="form-element">
                  <input type="email" name="username" id="username">
                </div>
              </div>
              <div class="form-group">
                <div class="form-element">
                  <label for="password">Password</label>
                </div>
                <div class="form-element">
                  <input type="password" name="password" id="password">
                </div>
              </div>
              <div class="form-group">
               <input type="submit" value="Login">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" ></script>
    <script src="assets/js/login.js"></script>
    <script src="assets/js/functions.js"></script>
    
    

  </body>
</html>
