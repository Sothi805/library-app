pipeline {
agent any

```
environment {
    GIT_REPO_URL = "https://github.com/Sothi805/social-media-app.git"
    IMAGE_NAME = "vethsothi/social-media-app"
    DOCKER_CREDENTIALS = "dockerhub_creds"
    REMOTE_SSH_KEY = "REMOTE_SSH_KEY"
    REMOTE_USER = "ubuntu"
    REMOTE_HOST = "54.226.229.237"
    REMOTE_PATH = "/home/ubuntu/deploy"
}

parameters {
    gitParameter(
        name: 'TAG',
        type: 'PT_TAG',
        defaultValue: '',
        description: 'Select Git tag to build',
        useRepository: 'https://github.com/Sothi805/social-media-app.git',
        sortMode: 'DESCENDING_SMART'
    )
    gitParameter(
        name: 'BRANCH',
        type: 'PT_BRANCH',
        defaultValue: 'main',
        description: 'Select branch if no tag',
        useRepository: 'https://github.com/Sothi805/social-media-app.git',
        sortMode: 'ASCENDING_SMART'
    )
}

stages {
    stage('Checkout') {
        steps {
            script {
                if (params.TAG) {
                    echo "📦 Checking out tag: ${params.TAG}"
                    checkout([$class: 'GitSCM',
                        branches: [[name: "refs/tags/${params.TAG}"]],
                        userRemoteConfigs: [[url: env.GIT_REPO_URL]]
                    ])
                } else {
                    echo "📦 Checking out branch: ${params.BRANCH}"
                    checkout([$class: 'GitSCM',
                        branches: [[name: params.BRANCH]],
                        userRemoteConfigs: [[url: env.GIT_REPO_URL]]
                    ])
                }
            }
        }
    }

    stage('Build Docker image') {
        steps {
            script {
                sh "docker build -t ${IMAGE_NAME}:${params.TAG} ."
                echo "✅ Built image ${IMAGE_NAME}:${params.TAG}"
            }
        }
    }

    stage('Push image to Docker Hub') {
        steps {
            withCredentials([usernamePassword(credentialsId: "${DOCKER_CREDENTIALS}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                sh '''
                    echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                    docker push ${IMAGE_NAME}:${TAG}
                    docker tag ${IMAGE_NAME}:${TAG} ${IMAGE_NAME}:latest
                    docker push ${IMAGE_NAME}:latest
                '''
            }
        }
    }

    stage('Deploy to EC2') {
        steps {
            withCredentials([sshUserPrivateKey(credentialsId: "${REMOTE_SSH_KEY}", keyFileVariable: 'SSH_KEY')]) {
                script {
                    sh """
                        echo "🚀 Copying configuration and .env to EC2..."
                        scp -i $SSH_KEY -o StrictHostKeyChecking=no -r docker docker-compose.yml .env ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}/

                        echo "⚙️ Deploying application on EC2..."
                        ssh -i $SSH_KEY -o StrictHostKeyChecking=no ${REMOTE_USER}@${REMOTE_HOST} '
                            set -e
                            cd ${REMOTE_PATH}

                            sudo apt-get update -y
                            sudo apt-get install -y docker-compose-plugin

                            echo "🧹 Cleaning old containers..."
                            sudo docker compose down || true

                            echo "⬇️ Pulling latest image..."
                            sudo docker pull ${IMAGE_NAME}:latest

                            echo "🧱 Starting updated containers..."
                            sudo docker compose up -d

                            echo "🛠  Laravel setup inside container..."
                            sudo docker exec -i social-media-app bash -c "
                                cd /var/www/html &&
                                if ! grep -q 'APP_KEY=' .env || grep -q 'APP_KEY=$' .env; then
                                    php artisan key:generate;
                                fi &&
                                php artisan migrate --force &&
                                php artisan config:clear &&
                                php artisan cache:clear &&
                                php artisan route:clear &&
                                php artisan view:clear &&
                                php artisan config:cache &&
                                chown -R www-data:www-data storage bootstrap/cache &&
                                chmod -R 775 storage bootstrap/cache
                            "
                        '
                    """
                }
            }
        }
    }
}

post {
    success {
        echo "✅ Deployment completed successfully for ${IMAGE_NAME}:${params.TAG}"
    }
    failure {
        echo "❌ Deployment failed. Please check the logs for details."
    }
}
```

}
