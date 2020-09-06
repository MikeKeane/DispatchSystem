# Dispatch System
Gear4Music OO Programming Challenge 02c

## Folder Structure Description
bin: used to store the Console Application to demo the class structure

data-store: used to store the active Dispatch Period and consignment files to be uploaded/sent to the Couriers  

src/commands: holds the commands for use in the Console Application

src/objects: stores the class structure

### Code Commenting
I have commented the code to describe each class and its purpose.

### Demo Console App
The demo application I have provided just offers a simple console interface for starting and stopping a dispatch period and also adding consignments. 

To view available commands you can run 'php bin/dispatchConsole.php'.  

To start the dispatch period you'd need to run 'php bin/dispatchConsole.php start'.  

To add a consignment to the dispatch period you would run 'php bin/dispatchConsole.php add-consignment [courierName] [description]
courierName - can be either "ANC", "DPD" or "Royal Mail"
description - is optional and can be any string

To stop the dispatch period you'd need to run 'php bin/dispatchConsole.php stop'.
