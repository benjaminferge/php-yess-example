protoc --proto_path=./protos --php_out=./protos/lib --grpc_out=./protos/lib --plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin ./protos/yess.proto

