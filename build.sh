#!/usr/bin/env bash
# build, tag, and push docker images

# exit if a command fails
set -o errexit

# exit if required variables aren't set
set -o nounset

#set environment variables
nginx_version="1.19.2"
build_version="1.0"
core_count="$(grep -c ^processor /proc/cpuinfo)"

# create docker run image
docker build \
	--build-arg NGINX_VER="$nginx_version" \
	--build-arg CORE_COUNT="$core_count" \
	-t docker.galenguyer.com/chef/universalanimalapi:latest \
	-f Dockerfile .

docker push docker.galenguyer.com/chef/universalanimalapi:latest
