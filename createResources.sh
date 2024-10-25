#!/bin/bash

# Array to hold resource names
resources=()

# Function to add resource names to the array
add_resources() {
    while true; do
        read -p "Enter the resource name (or 0 to finish): " resource
        if [[ "$resource" == "0" ]]; then
            break
        fi
        resources+=("$resource")
    done
}

# Function to generate resources using artisan command
generate_resources() {
    for resource in "${resources[@]}"; do
        echo "Generating Filament resource for: $resource"
        php artisan make:filament-resource "$resource" --generate
    done
}

# Main script execution
add_resources
generate_resources