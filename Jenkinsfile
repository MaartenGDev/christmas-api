pipeline {
    agent { label 'webserver' }

    environment {
        PROD_USER = credentials('PROD_USER')
        CHRISTMAS_API_DB_NAME = credentials('CHRISTMAS_API_DB_NAME')
        CHRISTMAS_API_DB_USER = credentials('CHRISTMAS_API_DB_USER')
        CHRISTMAS_API_DB_PASSWORD = credentials('CHRISTMAS_API_DB_PASSWORD')
        CHRISTMAS_API_SEED_USERS = credentials('CHRISTMAS_API_SEED_USERS')
        CHRISTMAS_API_SENTRY_DSN = credentials('CHRISTMAS_API_SENTRY_DSN')
        CHRISTMAS_API_UNSPLASH_TOKEN = credentials('CHRISTMAS_API_UNSPLASH_TOKEN')
        RELEASE_DOMAIN = 'christmas-api.maartendev.me'
        DEPLOY_PATH = "/var/www/${RELEASE_DOMAIN}"
    }
    stages {
      stage('Configure environment variables for application'){
            steps {
                sh "cp .env.example .env"
                sh 'sed -i -e "s/APP_ENV=local/APP_ENV=production/g" .env'
                sh 'sed -i -e "s/DB_DATABASE=homestead/DB_DATABASE=${CHRISTMAS_API_DB_NAME}/g" .env'
                sh 'sed -i -e "s/DB_USERNAME=homestead/DB_USERNAME=${CHRISTMAS_API_DB_USER}/g" .env'
                sh 'sed -i -e "s/DB_PASSWORD=secret/DB_PASSWORD=\"${CHRISTMAS_API_DB_PASSWORD}\"/g" .env'
                sh 'sed -i -e "s/DEFAULT_USERS=/DEFAULT_USERS=\"${CHRISTMAS_API_SEED_USERS}\"/g" .env'
                sh 'sed -i -e "s/SENTRY_DSN=/SENTRY_DSN=\"${CHRISTMAS_API_SENTRY_DSN}\"/g" .env'
                sh 'sed -i -e "s/UNSPLASH_TOKEN=/UNSPLASH_TOKEN=\"${CHRISTMAS_API_UNSPLASH_TOKEN}\"/g" .env'
            }
        }
        stage('Install composer dependencies'){
            steps {
                sh 'composer install --optimize-autoloader'
            }
        }
        stage('Clear cache'){
            steps {
                sh 'php artisan cache:clear'
                sh 'php artisan config:clear'
            }
        }
        stage('Configure application secrets'){
            steps {
                sh 'php artisan jwt:secret -f'
            }
        }
        stage('Run migrations'){
            steps {
                sh 'php artisan migrate --force'
             }
        }
        stage('Warm up cache'){
            steps {
                sh 'php artisan config:cache'
                sh 'php artisan route:cache'
            }
        }
        stage('Build assets'){
            steps {
                sh 'npm install'
                sh 'npm run prod'
            }
        }
        stage('deploy'){
            steps {
                sh "rm -rf ${DEPLOY_PATH}/*"
                sh "cp -rp ${WORKSPACE}/* ${DEPLOY_PATH}/"
            }
        }

        stage('Configure folder permissions'){
            steps {
                sh "sudo chown -R www-data:${PROD_USER} ${DEPLOY_PATH}/storage/"
                sh "cp ${WORKSPACE}/.env ${DEPLOY_PATH}/.env"
            }
        }
    }
}