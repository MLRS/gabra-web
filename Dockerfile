# Use Node.js 22 as the base image
FROM node:22.18 AS build

# Set the working directory
WORKDIR /app

# Copy package.json and package-lock.json
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application code
COPY . .

# Specify build-time env vars
ARG VITE_BASE_URL
ENV VITE_BASE_URL=$VITE_BASE_URL

ARG VITE_API_URL
ENV VITE_API_URL=$VITE_API_URL

# Build the project (adjust if you use yarn or a different build command)
RUN npm run build

# ---

# Use minimal nginx as production image
FROM nginx:alpine

# Remove default HTML 
RUN rm -rf /usr/share/nginx/html/*

# Copy built site from build image
COPY --from=build /app/dist /usr/share/nginx/html

# Run nginx server as main process
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
