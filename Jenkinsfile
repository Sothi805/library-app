pipeline {
    agent any

    environment {
        GIT_REPO_URL       = "https://github.com/Sothi805/library-app.git"
        IMAGE_NAME         = "vethsothi/library-app"
        DOCKER_CREDENTIALS = "dockerhub_creds"
        REMOTE_SSH_KEY     = "REMOTE_SSH_KEY"
        REMOTE_USER        = "ubuntu"
        REMOTE_HOST        = "54.242.230.134"
        REMOTE_PATH        = "/home/ubuntu/deploy"
        ENV_CREDENTIAL_ID  = "library-env"
    }

    parameters {
        gitParameter(
            name: 'TAG',
            type: 'PT_TAG',
            defaultValue: '',
            description: 'Select Git tag to build',
            useRepository: 'https://github.com/Sothi805/library-app.git',
            sortMode: 'DESCENDING_SMART'
        )
        gitParameter(
            name: 'BRANCH',
            type: 'PT_BRANCH',
            defaultValue: 'main',
            description: 'Select branch if no tag',
            useRepository: 'https://github.com/Sothi805/library-app.git',
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
                    def tag = (params.TAG?.trim()) ? params.TAG : "latest"
                    sh "docker build -t ${IMAGE_NAME}:${tag} ."
                    echo "✅ Built image ${IMAGE_NAME}:${tag}"
                }
            }
        }

        stage('Push image to Docker Hub') {
            steps {
                script {
                    def tag = (params.TAG?.trim()) ? params.TAG : "latest"
                    withCredentials([usernamePassword(
                        credentialsId: "${DOCKER_CREDENTIALS}",
                        usernameVariable: 'DOCKER_USER',
                        passwordVariable: 'DOCKER_PASS'
                    )]) {
                        sh """#!/bin/bash
                        set -e
                        echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                        docker push ${IMAGE_NAME}:${tag}
                        if [[ "${tag}" != "latest" ]]; then
                            docker tag ${IMAGE_NAME}:${tag} ${IMAGE_NAME}:latest
                            docker push ${IMAGE_NAME}:latest
                        fi
                        """
                    }
                }
            }
        }

        stage('Deploy to EC2') {
            steps {
                withCredentials([
                    sshUserPrivateKey(credentialsId: "${REMOTE_SSH_KEY}", keyFileVariable: 'SSH_KEY'),
                    file(credentialsId: "${ENV_CREDENTIAL_ID}", variable: 'ENV_FILE')
                ]) {
                    script {
                        def tag = (params.TAG?.trim()) ? params.TAG : "latest"

                        sh """#!/bin/bash
                        set -e
                        echo "⚙️ Preparing EC2 deployment folder..."
                        ssh -i $SSH_KEY -o StrictHostKeyChecking=no ${REMOTE_USER}@${REMOTE_HOST} "mkdir -p ${REMOTE_PATH}"

                        echo "🚀 Copying configuration and .env to EC2..."
                        scp -i $SSH_KEY -o StrictHostKeyChecking=no -r docker docker-compose.yml ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}/
                        ssh -i $SSH_KEY -o StrictHostKeyChecking=no ${REMOTE_USER}@${REMOTE_HOST} "rm -f ${REMOTE_PATH}/.env"
                        scp -i $SSH_KEY -o StrictHostKeyChecking=no $ENV_FILE ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}/.env

                        echo "⚙️ Deploying application on EC2..."
                        ssh -i $SSH_KEY -o StrictHostKeyChecking=no ${REMOTE_USER}@${REMOTE_HOST} "bash -s" <<'EOF'
                            set -e
                            cd ${REMOTE_PATH}

                            echo "🐳 Installing Docker & Docker Compose..."
                            if ! command -v docker >/dev/null 2>&1; then
                                curl -fsSL https://get.docker.com | sudo bash
                                sudo usermod -aG docker ubuntu
                            fi

                            echo "🧹 Cleaning old containers..."
                            sudo docker compose down || true

                            echo "⬇️ Pulling latest image..."
                            sudo docker pull ${IMAGE_NAME}:${tag}

                            echo "🧱 Starting updated containers..."
                            sudo docker compose up -d

                            echo "🛠 Running Laravel setup inside container..."
                            sudo docker exec -i library_app bash -c '
                                cd /var/www/html &&
                                echo "⏳ Waiting for database connection..." &&
                                until php -r "new mysqli(getenv(\\"DB_HOST\\"), getenv(\\"DB_USERNAME\\"), getenv(\\"DB_PASSWORD\\"));" 2>/dev/null; do
                                    echo "🕒 MySQL not ready yet... retrying in 5s";
                                    sleep 5;
                                done &&
                                echo "✅ Database is ready! Running migrations..." &&
                                php artisan migrate --force &&
                                php artisan optimize:clear &&
                                php artisan config:cache &&
                                php artisan route:cache &&
                                php artisan view:cache &&
                                chown -R www-data:www-data storage bootstrap/cache &&
                                chmod -R 775 storage bootstrap/cache
                            '
                        EOF
                        """
                    }
                }
            }
        }
    }

    post {
        success {
            echo "✅ Deployment completed successfully for ${IMAGE_NAME}:${params.TAG ?: 'latest'}"
        }
        failure {
            echo "❌ Deployment failed. Please check the logs for details."
        }
    }
}
