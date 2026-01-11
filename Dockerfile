# Use Node.js 22 as the base image
FROM node:22.18

# Set the working directory
WORKDIR /app

# Copy package.json and package-lock.json
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application code
COPY . .

# Build the project (adjust if you use yarn or a different build command)
RUN npm run build
