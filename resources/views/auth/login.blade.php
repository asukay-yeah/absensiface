<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login</title>
    <link href="https://fonts.cdnfonts.com/css/sofia-pro" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            font-family: "Sofia Pro";
            margin: 0;
            padding: 0;
        }

        canvas {
            display: block;
        }
    </style>
</head>

<body>
    <div class="flex w-screen flex-wrap text-slate-800">
        <div class="flex w-full flex-col md:w-1/2">
            <div class="flex justify-center pt-6 md:justify-start md:pl-10">
                <img src="{{ asset('asset/balmon.png') }}" class="md:w-1/4 w-1/2" alt="">
            </div>
            <div
                class="md:mx-auto mx-8 flex flex-col justify-center md:justify-start lg:w-[32rem] mt-[4rem] md:mt-[6rem]">
                <div class="w-full flex justify-center md:justify-start">
                    <img src="https://diskominfo.penajamkab.go.id/wp-content/uploads/2020/02/logo-kominfo.png"
                        class="w-16 h-16 mb-6 items-center">
                </div>
                <p class="text-center text-3xl font-bold md:leading-tight md:text-left md:text-3xl">Get Started</p>
                <p class="mt-2 text-center welcome md:text-left text-gray-400">Welcome to Balmon Attendance - Please
                    login to your account.</p>
                <hr class="border-t-1 border-gray-300 my-10">
                <form class="flex flex-col items-stretch" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="flex flex-col form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="text-sm font-thin mb-1 ml-1">Email</label>
                        <div
                            class="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-800 border-gray-200 hover:border-blue-800">
                            <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full flex-shrink appearance-none border-gray-700 bg-white py-2 px-4 text-base text-gray-600 placeholder-gray-300 focus:outline-none"
                                placeholder="" />
                        </div>

                    </div>
                    <div class="mb-4 flex flex-col pt-4 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="text-sm font-thin mb-1 ml-1">Password</label>
                        <div
                            class="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-800 border-gray-200 hover:border-blue-800">
                            <input type="password" id="password" name="password" required
                                class="w-full flex-shrink appearance-none bg-white py-2 px-4 text-base text-gray-600 placeholder-gray-300 focus:outline-none"
                                placeholder="" />
                        </div>
                    </div>
                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong class="text-red-600">{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                    @if ($errors->has('password'))
                    <span class="help-block">
                        <strong class="text-red-600">{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                    <button type="submit"
                        class=" w-full rounded-md bg-blue-800 px-4 py-2 mt-5 text-center text-base font-semibold text-white hover:text-blue-800 shadow-md border border-blue-600 ring-blue-600 ring-offset-2 transition-colors duration-300 hover:bg-white focus:ring-2">Login</button>
                </form>

                <div class="text-center my-4">
                    <p class="whitespace-nowrap font-semibold text-gray-900 underline underline-offset-4">or</p>
                </div>
                <a href="{{ url('/absen') }}"
                    class="w-full rounded-md bg-white px-4 py-2 text-center text-base font-semibold text-blue-800 hover:text-white shadow-md border border-blue-600 ring-blue-600 ring-offset-2 transition-colors duration-300 hover:bg-blue-800 focus:ring-2">Guest
                </a>
            </div>
        </div>
        <div class="relative hidden h-screen select-none md:block md:w-1/2 p-4">
            <div class="relative w-full h-full">
                <img src="{{ asset('asset/gedung.jpg') }}" class="w-full h-full rounded-xl object-cover" alt="">
                <div class="absolute inset-0 bg-blue-900 opacity-40 rounded-xl"></div>
            </div>
        </div>



    </div>


</body>

</html>