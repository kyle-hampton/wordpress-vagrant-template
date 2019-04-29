# wordpress-vagrant-template
This project uses vagrant as a Wordpress template and server

-- Setting up a local environment for Wordpress --

1.) Install Virtual box and Vagrant to local Machine

2.) Setting up localhost address for Wordpress
    -- Run "sudo nano /etc/hosts"
    -- In nano terminal paste IP address and project name ("192.168.33.10 example.test") into document under existing document content
    -- to exit and save new address hold "control -x" and when prompted press 'y' and enter to save document

3.) Run "vagrant up" once vagrant is installed inside "example.test" directory

4.) Run "vagrant ssh" to log into virtual machine and running Wordpress server
