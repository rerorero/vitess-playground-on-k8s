# vitess-playground-on-k8s

```
go get vitess.io/vitess/go/cmd/vtctlclient

pip install -r requirments.txt
ansible-playbook -e "kubeconfig=<PATH TO KUBECONFIG>" site.yml 
```

Creates new shard.
```
ansible-playbook -e "kubeconfig=<PATH TO KUBECONFIG>" -e "id_base=100 shard=0 keyspace=ks1" new-shard.yml
```
