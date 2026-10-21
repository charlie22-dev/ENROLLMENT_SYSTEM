@props(['status'])
@php
$colors = [
    'draft'             => 'bg-gray-100 text-gray-700 border-gray-300',
    'pending_advising'  => 'bg-blue-100 text-blue-700 border-blue-300',
    'pending_admission' => 'bg-purple-100 text-purple-700 border-purple-300',
    'pending_registrar' => 'bg-orange-100 text-orange-700 border-orange-300',
    'pending_cashier'   => 'bg-yellow-100 text-yellow-700 border-yellow-300',
    'enrolled'          => 'bg-green-100 text-green-700 border-green-300',
    'returned'          => 'bg-amber-100 text-amber-700 border-amber-300',
    'rejected'          => 'bg-red-100 text-red-700 border-red-300',
    'cancelled'         => 'bg-red-50 text-red-500 border-red-200',
];
$labels = [
    'draft'             => 'Draft',
    'pending_advising'  => 'Pending Advising',
    'pending_admission' => 'Pending Admission',
    'pending_registrar' => 'Pending Registrar',
    'pending_cashier'   => 'Pending Cashier',
    'enrolled'          => 'Enrolled',
    'returned'          => 'Returned',
    'rejected'          => 'Rejected',
    'cancelled'         => 'Cancelled',
];
$color = $colors[$status] ?? 'bg-gray-100 text-gray-700 border-gray-300';
$label = $labels[$status] ?? ucfirst($status);
@endphp
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $color }}">
    {{ $label }}
</span>
