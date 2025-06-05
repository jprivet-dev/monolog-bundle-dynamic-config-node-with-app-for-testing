start:
	symfony server:start --dir="app" --daemon

stop:
	symfony server:stop --dir="app"

cc:
	cd app && symfony console clear:cache


