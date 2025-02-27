<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition System</title>
    <link href="https://fonts.cdnfonts.com/css/sofia-pro" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="{{ asset('js/absen.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>


    <style>
        * {
            font-family: 'Sofia Pro', sans-serif;
            margin: 0;
            padding: 0;
        }


        /* From Uiverse.io by vinodjangid07 */
        .Btn {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition-duration: .3s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
        }

        /* plus sign */
        .sign {
            width: 100%;
            transition-duration: .3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sign svg {
            width: 17px;
        }

        .sign svg path {
            fill: white;
        }

        /* text */
        .text {
            position: absolute;
            right: 0%;
            width: 0%;
            opacity: 0;
            color: white;
            font-size: 0.9em;
            font-weight: 600;
            transition-duration: .3s;
        }

        /* hover effect on button width */
        .Btn:hover {
            width: 150px;
            border-radius: 40px;
            transition-duration: .3s;
        }

        .Btn:hover .sign {
            width: 30%;
            transition-duration: .3s;
            padding-left: 20px;
        }

        /* hover effect button's text */
        .Btn:hover .text {
            opacity: 1;
            width: 70%;
            transition-duration: .3s;
            padding-right: 10px;
        }

        /* button click effect*/
        .Btn:active {
            transform: translate(2px, 2px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
            }

            to {
                transform: translateY(0);
            }
        }

        #seatModal {
            animation: fadeIn 0.3s ease-out;
        }

        #seatModal>div {
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 24px;
            margin: 20px;
            width: 90%;
            max-width: 500px;
        }

        #seatContainer button:not(:disabled):hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }

        #confirmBtn {
            background-color: #4F46E5;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }

        #confirmBtn:hover {
            background-color: #4338CA;
        }

        #cancelBtn {
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }

        #cancelBtn:hover {
            background-color: #F3F4F6;
        }
    </style>
</head>

<body class="">
    <div class="h-screen w-full flex items-center justify-center flex-col relative">
        <p class="mb-2">Jakarta, Indonesia</p>
        <div id="datetime" class="md:text-xl text-xl font-medium text-white mb-8 px-6 py-3 rounded-md bg-blue-900">
        </div>
        <div class="md:text-6xl text-4xl font-semibold tracking-tighter">Face Recognition System</div>
        <div class="md:mt-5 mt-2 font-thin text-[#999999]">Note : Please fit your face into the camera</div>

        <div class="my-6 w-full items-center flex justify-center">
            <div class="relative aspect-video mb-4 rounded-lg overflow-hidden md:w-1/2 mx-8">
                <video id="video" class="w-full h-full object-cover transform scale-x-[-1]" autoplay muted></video>
                <canvas id="canvas" class="absolute top-0 left-0 w-full h-full"></canvas>

                <!-- Warning Button -->
                <div x-data="{ showPrivacyPolicy: false }">
                    <button @click="showPrivacyPolicy = true" id="warning"
                        class="shadow-sm shadow-black backdrop-blur absolute right-0 top-0 flex items-center justify-center bg-[#F5C227] mt-2 mr-2 md:mt-3 md:mr-3 md:w-10 md:h-10 h-8 w-8 rounded-md">
                        <div class="text-center text-white text-2xl mt-1">!</div>
                    </button>
                    <!-- Warning Modal -->
                    <div x-show="showPrivacyPolicy" class="fixed z-10 inset-0 flex items-center justify-center">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        <div class="relative bg-white rounded-lg overflow-hidden shadow-xl max-w-screen-md w-full m-4"
                            x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200 transform opacity-100 scale-100"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            x-cloak>
                            <!-- Modal panel -->
                            <div class="px-6 py-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Face Recognition System - FAQ &
                                    Privacy Policy</h3>
                            </div>
                            <div class="prose max-w-screen-md p-6 overflow-y-auto border border-gray-300"
                                style="max-height: 70vh;">
                                <h2 class="text-2xl font-bold mb-4">Face Recognition System - FAQ & Privacy Policy</h2>
                                <p class="mb-4">This FAQ & Privacy Policy describes how we collect, use, and disclose
                                    information that we obtain through our website and services.</p>
                                <h3 class="text-lg font-semibold mb-2">What is a Face Recognition System?</h3>
                                <p>Face Recognition System is a technology used to recognize a person's face with the
                                    help of artificial intelligence (AI) and machine learning.</p>
                                <ul class="list-disc ml-6 mb-4">
                                    <li>The system takes an image of the face from the camera, analyzes it, and matches
                                        it with the data stored in the system.</li>
                                </ul>
                                <h3 class="text-lg font-semibold mb-2">How to use this system?</h3>
                                <ul class="list-disc ml-6 mb-4">
                                    <li>Make sure your face is centered on the camera and clearly visible.</li>
                                    <li>Avoid lighting that is too bright or dark.</li>
                                    <li>Wait for the system to recognize your face.</li>
                                </ul>
                                <h3 class="text-lg font-semibold mb-2">What Data to Collect?</h3>
                                <p>The system can collect the following data:</p>
                                <ul class="list-disc ml-6 mb-4">
                                    <li>Face image taken from the camera.</li>
                                    <li>Time and date information when the scan was performed.</li>
                                    <li>Metadata such as lighting level or face angle.</li>
                                </ul>
                                <h3 class="text-lg font-semibold mb-2">How will my data be used?</h3>
                                <p>We use your facial data for:</p>
                                <ul class="list-disc ml-6 mb-4">
                                    <li>Recognize and verify your identity in the system.</li>
                                    <li>Improve system accuracy with AI-based learning.</li>
                                    <li>Provide a safer and more efficient experience for users.</li>
                                </ul>
                                <h3 class="text-lg font-semibold mb-2">Is My Face Data Stored?</h3>
                                <p>Depends on the system configuration:</p>
                                <ul class="list-disc ml-6 mb-4">
                                    <li>If used for momentary authentication, the face data will not be stored
                                        permanently.</li>
                                    <li>If used for user registration, facial images can be stored for future
                                        identification purposes.</li>
                                    <li>All stored data is encrypted and secured as per the security policy.</li>
                                </ul>
                                <h3 class="text-lg font-semibold mb-2">Is the System Secure?</h3>
                                <p>We use various security methods to protect user data:</p>
                                <ul class="list-disc ml-6 mb-4">
                                    <li>Data Encryption → All face data is encrypted to prevent unauthorized access.
                                    </li>
                                    <li>Restricted Access → Only authorized parties can access user data.</li>
                                    <li>Not Shared with Third Parties → We do not sell or share users' facial data
                                        without permission.</li>
                                </ul>
                                <h3 class="text-lg font-semibold mb-2">Can I Delete My Data?</h3>
                                <p class="mb-4">Yes, you can request to delete your face data through your account
                                    settings or contact our team.</p>
                                <h3 class="text-lg font-semibold mb-2">What If the System Doesn't Recognize My Face?
                                </h3>
                                <ul class="list-disc ml-6 mb-4">
                                    <li>Make sure the camera is not covered or blurry.</li>
                                    <li>Try changing the lighting to make it brighter but not too glaring.</li>
                                    <li>Make sure your face is not covered by a mask, sunglasses or other accessories.
                                    </li>
                                    <li>If the problem persists, try using a different device or contact our technical
                                        support.</li>
                                </ul>
                                <h3 class="text-lg font-semibold mb-2">How Can I Contact Support?</h3>
                                <p>If you have any questions or problems using this system, please contact us via:
                                    📧 Email: support@facerecognition.com
                                    📞 Phone: +62-xxx-xxxx-xxxx
                                </p>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex align-items justify-end p-4 gap-4 flex-row">
                                <button @click="showPrivacyPolicy = false" type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 sm:w-auto sm:text-sm">Accept</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class=" w-full">
            <div class="mx-auto max-w-4xl md:px-2 px-8 lg:px-8">
                <div class="rounded-lg bg-blue-900 shadow-lg p-3 pr-4">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="flex w-0 flex-1 items-center">
                            <span class="flex rounded-lg bg-white p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" aria-hidden="true" class="h-6 w-6 text-blue-700">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                    </path>
                                </svg>
                            </span>
                            <p class="ml-3 md:ml-5 h-auto font-light text-white whitespace-normal md:max-w-2xl">
                                <span class="inline md:text-base text-sm ">Selamat Pagi FazaNei Kanaya! Jam kedatangan
                                    berhasil dicatat.<br>Silahkan Duduk Sesuai Nomor Meja Yang Sudah ditentukan</span>
                            </p>
                        </div>
                        <div class=" flex-shrink-0 order-2 mt-0 w-auto">
                            <p class="md:flex hidden items-center justify-center rounded-md border border-transparent bg-white px-4 py-2 md:text-sm text-xs font-medium text-blue-800 shadow-sm hover:bg-blue-50"
                                href="#">Keep Fighting !
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->


        <!-- <button id="openModal" class="Btn mt-4 scale-125 bg-indigo-700">
            <div class="sign"><svg class="scale-150" role="img" focusable="false" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
                    <path
                        d="M 6.8718872,12.985621 C 6.6868,12.940599 6.5107171,12.795231 6.4324802,12.62315 c -0.061029,-0.133963 -0.061029,-0.390684 0,-0.523847 0.045021,-0.09805 0.2517186,-0.311547 0.3017421,-0.311547 0.016008,0 0.025012,-0.159075 0.025012,-0.44681 l 0,-0.446711 -0.1432675,0.01201 c -0.7052321,0.05703 -1.2266778,0.16928 -1.6333693,0.351566 l -0.144268,0.06503 0.061029,0.06503 c 0.1926907,0.205797 0.23361,0.512641 0.1002472,0.751354 -0.2489173,0.44581 -0.8869178,0.440607 -1.1161257,-0.01 -0.1757828,-0.344962 -0.031015,-0.735646 0.3901837,-1.052195 0.420198,-0.315949 1.1007185,-0.544257 1.9315098,-0.648206 l 0.1308617,-0.01601 0,-0.5708691 0,-0.5708689 0.6729169,0 0.672817,0 0,0.5708689 0,0.5708691 0.1308616,0.01601 c 1.1780549,0.14777 2.0272549,0.544457 2.3238947,1.085512 0.06403,0.117755 0.07503,0.161876 0.07403,0.323252 -5.01e-4,0.142967 -0.01301,0.210899 -0.05503,0.288936 -0.06403,0.120957 -0.2052968,0.250918 -0.3292552,0.302943 -0.1214572,0.05102 -0.3638714,0.04802 -0.4942328,-0.01 -0.1300613,-0.05302 -0.3196506,-0.258422 -0.3541668,-0.38278 -0.06303,-0.228708 -0.010005,-0.494133 0.1375648,-0.628396 0.034016,-0.03201 0.061029,-0.06503 0.061029,-0.07504 0,-0.01 -0.059028,-0.04302 -0.1308616,-0.07404 -0.4439091,-0.19139 -0.982963,-0.309145 -1.6322689,-0.356568 l -0.1308616,-0.01 0,0.438706 0,0.438607 0.1038489,0.07503 c 0.1925907,0.139866 0.2833335,0.341261 0.2593221,0.575571 -0.039018,0.38118 -0.4107934,0.648606 -0.7773661,0.559364 z M 3.7173013,8.8716829 C 3.4755874,8.8306636 3.2618868,8.6658859 3.1560369,8.4383788 3.0970091,8.3125195 3.0970091,8.303015 3.0970091,7.4701227 l -2.001e-4,-0.8410962 0.3584689,0 0.3584688,0 0.030014,0.2055969 c 0.051024,0.3487642 0.1475696,0.5870765 0.309946,0.762259 0.1389655,0.1498706 0.1822859,0.1605756 0.4183971,0.1035488 0.288636,-0.070033 0.9833632,-0.1670787 1.4280727,-0.2004945 0.2055968,-0.015007 0.6598108,-0.028013 1.0093754,-0.028013 0.3494646,0 0.8036786,0.013006 1.0092754,0.028013 0.4447095,0.033016 1.1395368,0.1309617 1.4279726,0.2004945 0.203796,0.049023 0.207998,0.049023 0.2990409,0 C 9.9834528,7.5781736 10.139126,7.2637255 10.187749,6.8073105 l 0.01701,-0.1557734 0.372575,0 0.372676,0 -0.01,0.8286904 c -0.01001,0.8039787 -0.01001,0.832292 -0.06503,0.953249 -0.110753,0.2405133 -0.311447,0.3913843 -0.578173,0.4345046 -0.19049,0.031015 -6.3998141,0.032015 -6.5811995,0 z M 4.4563494,7.2708288 C 4.4333386,7.1569752 4.0178428,2.5242931 4.018143,2.3790246 4.0187433,2.025258 4.1651122,1.6924012 4.4399417,1.4199729 4.6580444,1.203671 4.8094157,1.1152294 5.079643,1.0461968 c 0.1830862,-0.0470221 0.2800319,-0.049023 1.9270077,-0.049023 1.4575865,4.0019e-4 1.7597288,0.0100048 1.8936919,0.0380179 0.4747237,0.1118526 0.8934209,0.5056381 1.0378889,0.9760597 0.079038,0.2583217 0.071033,0.3914844 -0.1723812,2.8818574 -0.1299612,1.3274253 -0.239913,2.4171386 -0.2444151,2.4216407 0,0 -0.114554,-0.014007 -0.2446152,-0.040019 C 8.7296622,7.1624778 7.7500008,7.078038 6.9941448,7.077938 6.2571976,7.0778379 5.2809378,7.1639785 4.7313789,7.2767316 c -0.1347634,0.028013 -0.2491173,0.050024 -0.2542197,0.050024 -0.010005,0 -0.014007,-0.025012 -0.02101,-0.056026 z M 2.5853681,6.3008719 C 2.4863215,6.245846 2.4223913,6.16941 2.3967793,6.0745653 c -0.035016,-0.1288606 0,-0.23361 0.1204567,-0.348364 0.4588161,-0.4425085 1.2278784,-0.2227049 1.3893544,0.397187 0.02001,0.078037 0.037018,0.1567739 0.037018,0.1744822 0,0.04302 -1.2814036,0.045021 -1.3582398,0 z m 7.4897279,-0.039018 c 0,-0.1118527 0.08404,-0.3179498 0.175282,-0.4384065 0.187189,-0.246116 0.507439,-0.3731758 0.803079,-0.3185501 0.434204,0.080038 0.68032,0.4007888 0.515442,0.6712162 -0.08704,0.1431674 -0.14907,0.1542726 -0.858404,0.1542726 l -0.6363,0 9.01e-4,-0.069033 z" />
                </svg>
            </div>

            <div class="text ">Select Seat</div>
        </button> -->

        <!-- <div class="mt-4 text-gray-700">
            Selected seats: <span id="seatCount" class="font-bold">0</span>/10
        </div> -->

        <!-- Modal -->
        <div id="seatModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg mx-4 shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Choose a Seat</h2>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Maximum seats warning -->
                <div id="maxSeatsWarning" class="hidden mb-4 p-3 bg-yellow-100 text-yellow-700 rounded-lg">
                    You have reached the maximum seat selection limit (10 seats)
                </div>

                <div id="seatContainer" class="grid grid-cols-5 gap-2 mb-4">
                    <!-- Seats will be generated here -->
                </div>

                <div class="flex justify-between mt-6">
                    <button id="cancelBtn"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </button>
                    <button id="confirmBtn"
                        class="px-4 py-2 bg-indigo-700 text-white rounded-lg hover:bg-indigo-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                        disabled>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Confirm
                    </button>
                </div>
            </div>
        </div>

        <div id="notification"
            class="transition-opacity duration-500 fixed mx-6 md:mx-0 top-5 md:right-5 shadow-lg rounded-lg bg-white p-4 flex items-center justify-between md:w-full max-w-md border border-gray-300 opacity-0">
            <div class="flex items-center">
                <div class="pr-4">
                    <svg class="fill-current text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        width="24" height="24">
                        <path class="heroicon-ui"
                            d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 9a1 1 0 0 1-1-1V8a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg>
                </div>
                <div>
                    <div id="notification-title" class="text-base font-light ">Absen anda sudah tercatat hari ini</div>
                </div>
            </div>
            <button onclick="closeNotification()" class="ml-4 md:flex hidden text-gray-600 hover:text-gray-900">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                    <path class="heroicon-ui"
                        d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z" />
                </svg>
            </button>
        </div>

        <div id="notification"
            class="transition-opacity duration-500 fixed mx-6 md:mx-0 top-5 md:right-5 shadow-lg rounded-lg bg-white p-4 flex items-center justify-between md:w-full max-w-md border border-gray-300 opacity-0">
            <div class="flex items-center">
                <div class="pr-4">
                    <svg class="fill-current text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        width="22" height="22">
                        <path class="heroicon-ui"
                            d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.54-4.46a1 1 0 0 1 1.42-1.42 3 3 0 0 0 4.24 0 1 1 0 0 1 1.42 1.42 5 5 0 0 1-7.08 0zM9 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm6 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg>
                </div>
                <div>
                    <div id="notification-title" class="text-base font-light ">Terima Kasih! Anda Datang Tepat Waktu</div>
                </div>
            </div>
            <button onclick="closeNotification()" class="ml-4 md:flex hidden text-gray-600 hover:text-gray-900">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                    <path class="heroicon-ui"
                        d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z" />
                </svg>
            </button>
        </div>

        <div id="notification"
            class="transition-opacity duration-500 fixed mx-6 md:mx-0 top-5 md:right-5 shadow-lg rounded-lg bg-white p-4 flex items-center justify-between md:w-full max-w-md border border-gray-300 opacity-0">
            <div class="flex items-center">
                <div class="pr-4">
                    <svg class="fill-current text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        width="24" height="24">
                        <path class="heroicon-ui"
                            d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.54-4.54a5 5 0 0 1 7.08 0 1 1 0 0 1-1.42 1.42 3 3 0 0 0-4.24 0 1 1 0 0 1-1.42-1.42zM9 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm6 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg>
                </div>
                <div>
                    <div id="notification-title" class="text-base font-light ">Anda Datang Terlambat Hari Ini</div>
                </div>
            </div>
            <button onclick="closeNotification()" class="ml-4 md:flex hidden text-gray-600 hover:text-gray-900">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22">
                    <path class="heroicon-ui"
                        d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z" />
                </svg>
            </button>
        </div>

        <div id="toast-default"
            class="absolute right-5 top-28 flex items-center w-full max-w-xs p-3 text- bg-slate-800 text-white rounded-lg opacity-0 transition-opacity duration-500"
            role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 bg-blue-100 rounded-lg ">
                <svg class="fill-black w-8 h-auto" role="img" focusable="false" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
                    <path
                        d="M 6.8718872,12.985621 C 6.6868,12.940599 6.5107171,12.795231 6.4324802,12.62315 c -0.061029,-0.133963 -0.061029,-0.390684 0,-0.523847 0.045021,-0.09805 0.2517186,-0.311547 0.3017421,-0.311547 0.016008,0 0.025012,-0.159075 0.025012,-0.44681 l 0,-0.446711 -0.1432675,0.01201 c -0.7052321,0.05703 -1.2266778,0.16928 -1.6333693,0.351566 l -0.144268,0.06503 0.061029,0.06503 c 0.1926907,0.205797 0.23361,0.512641 0.1002472,0.751354 -0.2489173,0.44581 -0.8869178,0.440607 -1.1161257,-0.01 -0.1757828,-0.344962 -0.031015,-0.735646 0.3901837,-1.052195 0.420198,-0.315949 1.1007185,-0.544257 1.9315098,-0.648206 l 0.1308617,-0.01601 0,-0.5708691 0,-0.5708689 0.6729169,0 0.672817,0 0,0.5708689 0,0.5708691 0.1308616,0.01601 c 1.1780549,0.14777 2.0272549,0.544457 2.3238947,1.085512 0.06403,0.117755 0.07503,0.161876 0.07403,0.323252 -5.01e-4,0.142967 -0.01301,0.210899 -0.05503,0.288936 -0.06403,0.120957 -0.2052968,0.250918 -0.3292552,0.302943 -0.1214572,0.05102 -0.3638714,0.04802 -0.4942328,-0.01 -0.1300613,-0.05302 -0.3196506,-0.258422 -0.3541668,-0.38278 -0.06303,-0.228708 -0.010005,-0.494133 0.1375648,-0.628396 0.034016,-0.03201 0.061029,-0.06503 0.061029,-0.07504 0,-0.01 -0.059028,-0.04302 -0.1308616,-0.07404 -0.4439091,-0.19139 -0.982963,-0.309145 -1.6322689,-0.356568 l -0.1308616,-0.01 0,0.438706 0,0.438607 0.1038489,0.07503 c 0.1925907,0.139866 0.2833335,0.341261 0.2593221,0.575571 -0.039018,0.38118 -0.4107934,0.648606 -0.7773661,0.559364 z M 3.7173013,8.8716829 C 3.4755874,8.8306636 3.2618868,8.6658859 3.1560369,8.4383788 3.0970091,8.3125195 3.0970091,8.303015 3.0970091,7.4701227 l -2.001e-4,-0.8410962 0.3584689,0 0.3584688,0 0.030014,0.2055969 c 0.051024,0.3487642 0.1475696,0.5870765 0.309946,0.762259 0.1389655,0.1498706 0.1822859,0.1605756 0.4183971,0.1035488 0.288636,-0.070033 0.9833632,-0.1670787 1.4280727,-0.2004945 0.2055968,-0.015007 0.6598108,-0.028013 1.0093754,-0.028013 0.3494646,0 0.8036786,0.013006 1.0092754,0.028013 0.4447095,0.033016 1.1395368,0.1309617 1.4279726,0.2004945 0.203796,0.049023 0.207998,0.049023 0.2990409,0 C 9.9834528,7.5781736 10.139126,7.2637255 10.187749,6.8073105 l 0.01701,-0.1557734 0.372575,0 0.372676,0 -0.01,0.8286904 c -0.01001,0.8039787 -0.01001,0.832292 -0.06503,0.953249 -0.110753,0.2405133 -0.311447,0.3913843 -0.578173,0.4345046 -0.19049,0.031015 -6.3998141,0.032015 -6.5811995,0 z M 4.4563494,7.2708288 C 4.4333386,7.1569752 4.0178428,2.5242931 4.018143,2.3790246 4.0187433,2.025258 4.1651122,1.6924012 4.4399417,1.4199729 4.6580444,1.203671 4.8094157,1.1152294 5.079643,1.0461968 c 0.1830862,-0.0470221 0.2800319,-0.049023 1.9270077,-0.049023 1.4575865,4.0019e-4 1.7597288,0.0100048 1.8936919,0.0380179 0.4747237,0.1118526 0.8934209,0.5056381 1.0378889,0.9760597 0.079038,0.2583217 0.071033,0.3914844 -0.1723812,2.8818574 -0.1299612,1.3274253 -0.239913,2.4171386 -0.2444151,2.4216407 0,0 -0.114554,-0.014007 -0.2446152,-0.040019 C 8.7296622,7.1624778 7.7500008,7.078038 6.9941448,7.077938 6.2571976,7.0778379 5.2809378,7.1639785 4.7313789,7.2767316 c -0.1347634,0.028013 -0.2491173,0.050024 -0.2542197,0.050024 -0.010005,0 -0.014007,-0.025012 -0.02101,-0.056026 z M 2.5853681,6.3008719 C 2.4863215,6.245846 2.4223913,6.16941 2.3967793,6.0745653 c -0.035016,-0.1288606 0,-0.23361 0.1204567,-0.348364 0.4588161,-0.4425085 1.2278784,-0.2227049 1.3893544,0.397187 0.02001,0.078037 0.037018,0.1567739 0.037018,0.1744822 0,0.04302 -1.2814036,0.045021 -1.3582398,0 z m 7.4897279,-0.039018 c 0,-0.1118527 0.08404,-0.3179498 0.175282,-0.4384065 0.187189,-0.246116 0.507439,-0.3731758 0.803079,-0.3185501 0.434204,0.080038 0.68032,0.4007888 0.515442,0.6712162 -0.08704,0.1431674 -0.14907,0.1542726 -0.858404,0.1542726 l -0.6363,0 9.01e-4,-0.069033 z" />
                </svg>
            </div>
            <div class="ms-3 text-base font-normal items-center justify-center text-center">Nomor Meja : <span
                    class="text-3xl">18</span></div>
        </div>




    </div>
</body>

</html>