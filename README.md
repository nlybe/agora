Agora Classifieds Plugin
========================

![Elgg 2.3](https://img.shields.io/badge/Elgg-2.3-orange.svg?style=flat-square)

Elgg plugin for posting classifieds to community members using PayPal Payment Gateway.

Administrator can set who can post classifieds in settings, administrators or all users.

## Features
- Members are able to create classifieds/listing posts
- Post classifieds with several features:
    - Option to sell online units using PayPal payment gateway (PayPal API plugin is required)
    - Ad title and description
    - Ad location
    - Price and currency
    - No of available units
    - Photo of ad unit
    - tax and shipping cost
    - Tags, comments, access level
- Unlimited number of classifieds
- Unlimited number of classifieds categories
- Map of Classifieds with search options, if enabled by administrator
- Option to sell downloads/digital products, if enabled by administrator 
- Option for reviews and star ratings only from buyers
- Members must accept terms of use before posting ads, if enabled by administrator
- Buyer and seller receive notifications for PayPal transaction
- Automatically reduce the number of available units once payment is completed
- Automatically disable the classified if all the available units are sold out
- Option for posting classifieds in groups
- List view and gallery view of classifieds
- Widget on users profile for showing of their latest classifieds
- Widget on users profile displaying recent purchases of user
- Option for offline requests by sending private message to the seller, if enabled by administrator
- River announcements with image
- Notifications are send for each transaction to users specified by administrator
- Transactions log list in admin area
- Several configuration options


## Installation
Requires: Elgg 2.3.x or higher

1. Upload classifieds plugin in "/mod/" elgg folder and activate it
2. In "Administration/Configure/Settings/Agora Classifieds" you can configure several options
3. The PayPal API plugin is required in order to use PayPal as payment gateway.
4. The [Elgg-MapsAPI](https://github.com/nlybe/Elgg-MapsAPI) plugin is required if need to use location and map functionality.
5. Ratings plugin is suggested in order to allow comments and ratings only from buyers.
6. [HTML email handler]((https://github.com/ColdTrick/html_email_handler)) plugin is suggested for sending html emails.


## Future Tasks List
- [ ] Enable adaptive payments with PayPal
- [ ] Rebuild categories functionality 
- [ ] Validate cron job for rating reminder
- [ ] Remove language messages
