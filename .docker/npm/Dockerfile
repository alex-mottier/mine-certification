# Use an official Node.js runtime as a parent image
FROM node:18.15

# Set the working directory
WORKDIR /var/www/html

# Copy package.json and package-lock.json
COPY package.json package-lock.json ./

# Install any needed packages
RUN npm cache clean --force
RUN npm install

# Bundle app source
COPY . .

EXPOSE 3000 3001 5173
