<?php

return [

    'title' => 'Resource Inventory',
    'subtitle' => 'Manage human, material and financial resources for your projects',

    // Types
    'types' => [
        'human' => 'Human',
        'material' => 'Material',
        'financial' => 'Financial',
    ],

    // Actions
    'add_resource' => 'Add a resource',
    'edit_resource' => 'Edit resource',
    'search_resource' => 'Search a resource...',
    'no_resources_found' => 'No resources found',

    // Table
    'table' => [
        'name' => 'Name',
        'type' => 'Type',
        'quantity' => 'Quantity',
        'unit' => 'Unit',
        'cost' => 'Cost',
        'availability' => 'Availability',
        'assigned_to' => 'Assigned to',
        'project' => 'Project',
    ],

    // Form
    'form' => [
        'name' => 'Resource name',
        'type' => 'Resource type',
        'description' => 'Description',
        'quantity' => 'Available quantity',
        'unit' => 'Unit of measure',
        'unit_cost' => 'Unit cost',
        'supplier' => 'Supplier',
        'select_type' => 'Select a resource type',
    ],

    // Allocation
    'allocation' => [
        'title' => 'Resource Allocation',
        'allocate' => 'Allocate',
        'deallocate' => 'Deallocate',
        'allocated_quantity' => 'Allocated quantity',
        'available_quantity' => 'Available quantity',
        'allocation_history' => 'Allocation history',
        'no_allocations' => 'No allocations yet',
    ],

    // Statuses
    'statuses' => [
        'available' => 'Available',
        'allocated' => 'Allocated',
        'unavailable' => 'Unavailable',
        'maintenance' => 'Under maintenance',
    ],

];
