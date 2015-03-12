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

### 2015-03-10 rev. 2
* User import with password in migration
* Replaced Password-Hashing to event
* Added event listener for setState (Order)
* Email for New Users (on Migration and on Create through app)
* Added latest Ledger Action to Dashboard
* Added Balance and Given Credit Information to Dashboard

### 2015-03-12
* Add Widget to Dashboard
	* Total Balance
	* Einlage
	* Letzte Vorgänge
* On Create.success in user.js reset form and throw succes!
* Validation Response in MembersController::create
* Init Ledger on Member Create
* Ledger Add Today into date field

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
* Reorder Orders View Tab: 1. ByProduct 2. All Orders

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
* Redesign colors: Only <td> on checkbox will have colors indicating the completeness of the order.
* Email to user and member when order was sent as well as wenn order arrived
	* Do not inform change-applying user

### Users
* Edit User Group Settings needs update

### Members
* OnDelete Cascade!

### MemberLedger
* Limit Member Ledger Access!!!
* Add view "Säumige"
* SEPA IMPORT
* Seed ledger VWZ Table
* Connect Ledger VWZ Table with dropdown
* Add "Undo" Ledger Action
* Color in TotalBalance must work properly 
* ON Create Succes throw success!
* Dashboard: See History

### Shifts
* Create migration for shifts
* Create view for shifts
* Count and evaluate shifts from members

### Known Issues

