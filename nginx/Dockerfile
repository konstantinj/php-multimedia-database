FROM nginx:stable-alpine
RUN adduser -u 82 -D -S -G www-data www-data
COPY nginx.conf /etc/nginx/nginx.conf
CMD ["nginx", "-g", "daemon off;"]
EXPOSE 80
