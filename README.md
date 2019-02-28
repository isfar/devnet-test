### Steps to start the project 

1. Clone the project 
```bash
git clone git@github.com:isfar/devnet-test.git
```
2. Run the vagrant up command with `--provision` 
```bash
vagrant up --provision
```
3. Composer install
```bash
vagrant ssh -c 'composer install'
```
4. Turn on development mode
```bash
vagrant ssh -c 'composer development-enable'
```
5. Run the MO consumer in a standalone shell
```bash
vagrant ssh -c 'php public/index.php mo-consumer'
```

### Command line Tools

1. Run the monitoring script to get unprocessed MO count:
```bash
vagrant ssh -c 'php public/index.php mo-unprocessed-count' 
```
2. Run the script for deleting unprocessed MO's
```bash
vagrant ssh -c 'php public/index.php mo-remove-unprocessed'
```


### Server Endpoints

1. Create MO request: `http://localhost:8080/mo?msisdn=60123456789&operatorid=3&shortcodeid=8&text=ON+GAMES`
2. Show stats: `http://localhost:8080/stats`