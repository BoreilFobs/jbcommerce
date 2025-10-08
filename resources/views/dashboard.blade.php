@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
{{-- <div class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="card bg-white rounded-lg shadow-md overflow-hidden">
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm font-semibold text-gray-500">Order Statistics -
                        <div class="relative inline-block text-left">
                            <a class="font-bold text-blue-600 hover:text-blue-800 transition-colors duration-200" href="#" id="orders-month" data-toggle="dropdown">August</a>
                            <ul class="dropdown-menu absolute hidden right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <li class="dropdown-title px-4 py-2 text-sm text-gray-500">Select Month</li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">January</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">February</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">March</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">April</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">May</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">June</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">July</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 active bg-gray-200">August</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">September</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">October</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">November</a></li>
                                <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">December</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex justify-around items-center text-center border-t border-b py-3 mb-4">
                        <div class="flex-1 px-2">
                            <div class="text-xl font-bold text-gray-800">24</div>
                            <div class="text-xs text-gray-500">Pending</div>
                        </div>
                        <div class="flex-1 px-2">
                            <div class="text-xl font-bold text-gray-800">23</div>
                            <div class="text-xs text-gray-500">Completed</div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="card-icon-wrapper rounded-full h-12 w-12 flex items-center justify-center bg-blue-600 shadow-lg mr-4">
                        <i class="fas fa-archive text-white text-xl"></i>
                    </div>
                    <div class="flex-grow">
                        <div class="text-sm text-gray-500">Total Orders</div>
                        <div class="text-3xl font-bold text-gray-900">59</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg shadow-md p-6">
            <div class="relative">
                <div class="absolute inset-x-0 bottom-0 h-16 opacity-30">
                    <canvas id="balance-chart" class="w-full" height="80"></canvas>
                </div>
            </div>
            <div class="flex items-center">
                <div class="card-icon-wrapper rounded-full h-12 w-12 flex items-center justify-center bg-blue-600 shadow-lg mr-4">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
                <div class="flex-grow">
                    <div class="text-sm text-gray-500">Balance</div>
                    <div class="text-3xl font-bold text-gray-900">1,231,000FCFA</div>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-lg shadow-md p-6">
            <div class="relative">
                <div class="absolute inset-x-0 bottom-0 h-16 opacity-30">
                    <canvas id="sales-chart" class="w-full" height="80"></canvas>
                </div>
            </div>
            <div class="flex items-center">
                <div class="card-icon-wrapper rounded-full h-12 w-12 flex items-center justify-center bg-blue-600 shadow-lg mr-4">
                    <i class="fas fa-shopping-bag text-white text-xl"></i>
                </div>
                <div class="flex-grow">
                    <div class="text-sm text-gray-500">Sales</div>
                    <div class="text-3xl font-bold text-gray-900">4,732</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2">
            <div class="card bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Budget vs Sales</h4>
                </div>
                <canvas id="myChart" class="w-full" height="158"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="card bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Top 5 Products</h4>
                    <div class="relative inline-block text-left">
                        <a href="#" data-toggle="dropdown" class="bg-red-500 text-white font-bold py-1 px-3 rounded-full hover:bg-red-600 transition-colors duration-200">Month</a>
                        <ul class="dropdown-menu absolute hidden right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <li class="dropdown-title px-4 py-2 text-sm text-gray-500">Select Period</li>
                            <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Today</a></li>
                            <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Week</a></li>
                            <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 active bg-gray-200">Month</a></li>
                            <li><a href="#" class="dropdown-item block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">This Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    <div class="flex items-center py-4">
                        <img class="w-14 h-14 rounded mr-4" src="assets/img/products/product-3-50.png" alt="product">
                        <div class="flex-grow">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-gray-800">oPhone S9 Limited</div>
                                <div class="text-sm font-semibold text-gray-500">86 Sales</div>
                            </div>
                            <div class="mt-1">
                                <div class="flex items-center text-sm">
                                    <div class="w-16 h-2 bg-blue-600 rounded mr-2" style="width: 64%;"></div>
                                    <div class="text-gray-500">68,714FCFA</div>
                                </div>
                                <div class="flex items-center text-sm mt-1">
                                    <div class="w-16 h-2 bg-red-500 rounded mr-2" style="width: 43%;"></div>
                                    <div class="text-gray-500">38,700FCFA</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center py-4">
                        <img class="w-14 h-14 rounded mr-4" src="assets/img/products/product-4-50.png" alt="product">
                        <div class="flex-grow">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-gray-800">iBook Pro 2018</div>
                                <div class="text-sm font-semibold text-gray-500">67 Sales</div>
                            </div>
                            <div class="mt-1">
                                <div class="flex items-center text-sm">
                                    <div class="w-16 h-2 bg-blue-600 rounded mr-2" style="width: 84%;"></div>
                                    <div class="text-gray-500">107,133FCFA</div>
                                </div>
                                <div class="flex items-center text-sm mt-1">
                                    <div class="w-16 h-2 bg-red-500 rounded mr-2" style="width: 60%;"></div>
                                    <div class="text-gray-500">91,455FCFA</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center py-4">
                        <img class="w-14 h-14 rounded mr-4" src="assets/img/products/product-1-50.png" alt="product">
                        <div class="flex-grow">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-gray-800">Headphone Blitz</div>
                                <div class="text-sm font-semibold text-gray-500">63 Sales</div>
                            </div>
                            <div class="mt-1">
                                <div class="flex items-center text-sm">
                                    <div class="w-16 h-2 bg-blue-600 rounded mr-2" style="width: 34%;"></div>
                                    <div class="text-gray-500">3,717FCFA</div>
                                </div>
                                <div class="flex items-center text-sm mt-1">
                                    <div class="w-16 h-2 bg-red-500 rounded mr-2" style="width: 28%;"></div>
                                    <div class="text-gray-500">2,835FCFA</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center py-4">
                        <img class="w-14 h-14 rounded mr-4" src="assets/img/products/product-3-50.png" alt="product">
                        <div class="flex-grow">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-gray-800">oPhone X Lite</div>
                                <div class="text-sm font-semibold text-gray-500">28 Sales</div>
                            </div>
                            <div class="mt-1">
                                <div class="flex items-center text-sm">
                                    <div class="w-16 h-2 bg-blue-600 rounded mr-2" style="width: 45%;"></div>
                                    <div class="text-gray-500">13,972FCFA</div>
                                </div>
                                <div class="flex items-center text-sm mt-1">
                                    <div class="w-16 h-2 bg-red-500 rounded mr-2" style="width: 30%;"></div>
                                    <div class="text-gray-500">9,660FCFA</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center py-4">
                        <img class="w-14 h-14 rounded mr-4" src="assets/img/products/product-5-50.png" alt="product">
                        <div class="flex-grow">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-gray-800">Old Camera</div>
                                <div class="text-sm font-semibold text-gray-500">19 Sales</div>
                            </div>
                            <div class="mt-1">
                                <div class="flex items-center text-sm">
                                    <div class="w-16 h-2 bg-blue-600 rounded mr-2" style="width: 35%;"></div>
                                    <div class="text-gray-500">7,391FCFA</div>
                                </div>
                                <div class="flex items-center text-sm mt-1">
                                    <div class="w-16 h-2 bg-red-500 rounded mr-2" style="width: 28%;"></div>
                                    <div class="text-gray-500">5,472FCFA</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center pt-4 border-t border-gray-200 mt-4 space-x-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-600 rounded-full mr-2"></div>
                        <div class="text-sm text-gray-600">Selling Price</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <div class="text-sm text-gray-600">Budget Price</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2">
            <div class="card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800">Invoices</h4>
                    <a href="#" class="btn bg-red-500 text-white font-bold py-2 px-4 rounded-full hover:bg-red-600 transition-colors duration-200">
                        View More <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><a href="#" class="text-blue-600 hover:underline">INV-87239</a></td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">Kusnadi</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full inline-block">Unpaid</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 19, 2018</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="btn bg-blue-600 text-white font-bold py-1 px-3 rounded-full hover:bg-blue-700 transition-colors duration-200">Detail</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><a href="#" class="text-blue-600 hover:underline">INV-48574</a></td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">Susie Willis</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full inline-block">Paid</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 21, 2018</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="btn bg-blue-600 text-white font-bold py-1 px-3 rounded-full hover:bg-blue-700 transition-colors duration-200">Detail</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><a href="#" class="text-blue-600 hover:underline">INV-76824</a></td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">Muhamad Nuruzzaki</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full inline-block">Unpaid</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 22, 2018</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="btn bg-blue-600 text-white font-bold py-1 px-3 rounded-full hover:bg-blue-700 transition-colors duration-200">Detail</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><a href="#" class="text-blue-600 hover:underline">INV-84990</a></td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">Agung Ardiansyah</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full inline-block">Unpaid</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 22, 2018</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="btn bg-blue-600 text-white font-bold py-1 px-3 rounded-full hover:bg-blue-700 transition-colors duration-200">Detail</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><a href="#" class="text-blue-600 hover:underline">INV-87320</a></td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">Ardian Rahardiansyah</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full inline-block">Paid</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 28, 2018</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="btn bg-blue-600 text-white font-bold py-1 px-3 rounded-full hover:bg-blue-700 transition-colors duration-200">Detail</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-blue-600 text-white p-6 text-center">
                    <div class="flex items-center justify-center mb-2">
                        <i class="far fa-question-circle text-2xl"></i>
                    </div>
                    <h4 class="text-4xl font-bold">14</h4>
                    <div class="text-sm font-light">Customers need help</div>
                </div>
                <div class="divide-y divide-gray-200 p-4">
                    <a href="#" class="block py-3 px-2 hover:bg-gray-100 transition-colors duration-200">
                        <div class="font-semibold text-gray-800 mb-1">My order hasn't arrived yet</div>
                        <div class="text-sm text-gray-600 flex items-center">
                            <div>Laila Tazkiah</div>
                            <div class="w-1 h-1 bg-gray-400 rounded-full mx-2"></div>
                            <div class="text-blue-600 font-semibold">1 min ago</div>
                        </div>
                    </a>
                    <a href="#" class="block py-3 px-2 hover:bg-gray-100 transition-colors duration-200">
                        <div class="font-semibold text-gray-800 mb-1">Please cancel my order</div>
                        <div class="text-sm text-gray-600 flex items-center">
                            <div>Debra Stewart</div>
                            <div class="w-1 h-1 bg-gray-400 rounded-full mx-2"></div>
                            <div class="text-blue-600 font-semibold">5 hours ago</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> --}}
les statistique de vente et de stock et livraison seron present ici
@endsection