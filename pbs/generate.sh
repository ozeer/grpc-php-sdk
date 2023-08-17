#protoc --php_out=./src --grpc_out=./src --plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin passport.proto
protoc --php_out=../src --grpc_out=../src --plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin *.proto
#protoc --php_out=./src --grpc_out=./src member.proto
