#!/bin/sh
set -e

# Build the Docker image
docker build --tag gabra-web-build .

# Create a container from the image
container_id=$(docker create gabra-web-build)

# Copy /app/dist in the container to host
docker cp "$container_id":/app/dist ./

# Remove the container
docker rm "$container_id" 1>/dev/null

echo "Build complete"
