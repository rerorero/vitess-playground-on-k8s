# vitess-playground-on-k8s

```
go get vitess.io/vitess/go/cmd/vtctlclient

pip install -r requirments.txt
ansible-playbook -e "kubeconfig=<PATH TO KUBECONFIG>" site.yml 
```

`new-shard.yml` starts tablets and set up a new shard.
```
ansible-playbook -e "kubeconfig=<PATH TO KUBECONFIG>" -e "id_base=100 shard=0 keyspace=testks" new-shard.yml
```

`info.yml` shows Vitess endpoins.
```
ansible-playbook -e "kubeconfig=<PATH TO KUBECONFIG>" info.yml
```

### Run a example
Setup a new table and a schema.
```
vtctlclient -server ${vitess_endpoint} ApplySchema -sql "$(cat ./example/create_test_table.sql)" testks
vtctlclient -server ${vitess_endpoint} RebuildVSchemaGraph
```
Running `example/main.php` inserts some random generated rows to the table.
```
php main.php ${vitess_endpoint} 31002 testks
```
The followings shows an example of resharding.
```
# Apply VSchema
vtctlclient -server ${vitess_endpoint} ApplyVSchema -vschema "$(cat ./example/vschema.json)" testks
# Deploy tablets for new shard.
ansible-playbook -e "kubeconfig=<PATH TO KUBECONFIG>" -e "id_base=200 shard=-80 keyspace=testks" new-shard.yml
ansible-playbook -e "kubeconfig=<PATH TO KUBECONFIG>" -e "id_base=300 shard=80- keyspace=testks" new-shard.yml
```
