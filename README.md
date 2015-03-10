# FoodKoop
Web Application to manage small communities of (organic) food consumer's cooperation. Based on Laravel &amp; CanJS.

## Preface
Since this software will be developed primary for a German asociation we will keep discussions (at first) in German.
Code though will be commented in English, eventually.
It is not thought to give support to this piece of software, although everyone is free to contribute.
This might be a very specialized WebApplication, so until it will be finished (when it's done) we will follow the specifications of biokiste.org

## Technologies
It is thought to use 
* Laravel for the backend and HTML generation.
* CanJS for the client-side frontend.
* Bootstrap is used as CSS-Framework

## Features / Aims of the App

### Member Management

* *Members* are groups of individuals (*Users*) that live physically at the same address.
* Members are organized in *MemberGroups* (i.E. administration or service groups for different community tasks)
* Members can have *MemberStates*, which can define a classification of fees to pay.
* Members are subject to fees, such as a quarter-year-fee or an initial admission or credit to the FoodKoop. Those will be noted in the *MemberLedger* (Mitgliedskonto)
* Members can sign up to service tasks in a *Roster* (Dienstplan). The tasks that should be done should result from their *MemberGroup*
* Fullfillment of the member's duties as well as the ledger should be easily monitored.

### Order Management

* Members can do *Orders* to suit their needs of consumption.
* Orders are made off a *Product*-catalogue, that holds products of different *Merchants* (Anbieter).
* Orders can have OrderStates

## Release History

### 2015-03-08
* Make Product Overview Page
* Block ordering blocked products with 403
* Product Filtering in Product View
* Direct Ordering in Product View

### 2015-03-10
* Edit Product Attributes after Create

## Todo

### Orders

* Set Active to
	* grouped View (for admins)
	* newOrder Form (for ordinary members)
* Add Badges with (number of orders) to Übersicht
* Redefine All Orders view 
	* add Filters
	* add Limit
* Product Search with more than 3 letters to define better

### Products

* Make "Most famous Products" Page
* Generate Product XLS File
* Perform Search for Product Catalogue:
	* Add reload on count only when more than listable items (for larger databases)
* DELETE not working yet.
* Add Badges with product.count on product overview
* Direct Ordering in Product View only for admin
* Switching product_state only for admin
* After Updating Product also update in list view and product order list
* Allow only Admin to edit product attribute!

### Users

* User import with password in migration

### Known Issues

