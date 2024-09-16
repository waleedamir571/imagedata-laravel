@extends ('layouts.main')
@section('main-container')

<body>

<main class="main-content mt-0">
      <section>
        <div class="page-header min-vh-100">
          <div class="container">
            <div class="row">
              <div class="col-xl-4 col-md-6 d-flex flex-column mx-auto">
                <div class="card card-plain mt-8">
                  <div class="card-header pb-0 text-left bg-transparent">
                    <h3 class="font-weight-black text-dark display-6">
                      Welcome back
                    </h3>
                    <p class="mb-0">Welcome back! Please enter your details.</p>
                  </div>
                  <div class="card-body">
                    <form method="POST" action="{{ route('login.check') }}">
                        @csrf
                   
                      <label>Email Address</label>
                      <div class="mb-3">
                        <input
                          type="email"
                          class="form-control"
                          placeholder="Enter your email address"
                          name="email"
                          id="email"
                          aria-describedby="email-addon"
                        />
                      </div>
                      <label>Password</label>
                      <div class="mb-3">
                        <input
                          type="password"
                          class="form-control"
                          name="password"
                          placeholder="Enter password"
                          id="password"
                        />
                      </div>
                    
                      <div class="text-center">
                        <button
                          type="submit"
                          class="btn btn-dark btn-custom w-100 mt-4 mb-3"
                        >
                          Sign in
                        </button>
                      
                      </div>
                    </form>
                  </div>
                  <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <p class="mb-4 text-xs mx-auto">
                      Don't have an account?
                      <a href="{{ route('create.user') }}" class="text-dark font-weight-bold"
                        >Sign up</a
                      >
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div
                  class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none"
                >
                  <div
                    class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8"
                    style="
                      background-image: url('../img/login.png');
                    "
                  >
                    {{-- <div
                      class="blur mt-12 p-4 text-center border border-white border-radius-md position-absolute fixed-bottom m-4"
                    >
                       <h2 class="mt-3 text-dark font-weight-bold">
                        Enter our global community of clients.
                      </h2> 
                     <h6 class="text-dark text-sm mt-5">
                        Copyright © 2022 Corporate UI Design System by Creative
                        Tim.
                      </h6> 
                    </div> --}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- <div class="login-container">
        <h2>Login</h2>

        <form method="POST" action="{{ route('login.check') }}">
            @csrf
            <div class="form-group">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-custom">Login</button>
        </form>
    </div> -->

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->

@endsection