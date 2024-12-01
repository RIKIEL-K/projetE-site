<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('titre') | Utilisateur</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://www.paypal.com/sdk/js?client-id=AV8mC4qL2VW0FE1dh7N6aVh8VDyK_Q4y17GCJCe6jScWuvtl2h12RZiLcbiPIWmytljsfDsVPJXNkfRf&currency=CAD"></script>

  </head>
  <body>
    <!--Main Navigation-->
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">WG</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav hstack gap-3">
              <li class="nav-item">
                <a href="{{ route('cart.view') }}" class="btn btn-light position-relative nav-link">
                    <i class="bi bi-cart3"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill {{ $cartCount > 0 ? 'bg-danger' : 'bg-primary' }} ">
                        @if($cartCount > 0)
                            {{ $cartCount }}
                         @else
                            0
                        @endif

                    </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link btn btn-light" href="/">Nos produits</a>
              </li>
              <li class="nav-item">
                <a class="nav-link btn btn-warning bg-warning" href="{{route('userInfo')}}"><i class="bi bi-person-bounding-box"></i></a>
              </li>

              <li class="nav-item p-2">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger"><i class="bi bi-box-arrow-left"></i></button>
                        </form>
                    @endauth
              </li>
            </ul>
          </div>
        </div>
      </nav>
        <div class="container">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{session('success')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif
            @if (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{session('danger')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif
        </div>

        <div class="container mt-5">
            @yield('content')
        </div>
    <!--footer-->
     {{-- <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">
          Copyright &copy; Your Website 2023
        </p>
      </div>
    </footer> --}}
  </body>
</html>

