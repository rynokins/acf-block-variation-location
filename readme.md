# ACF Block Variation Location

Hey, welcome! For those of you who want to use the [Block Variations API](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-variations/) with your custom [ACF Blocks](https://www.advancedcustomfields.com/resources/acf_register_block_type/), you can use this to register ACF Field Groups for each of your block variations!

This solution is built using the work [@davidwebca](https://github.com/davidwebca) did for [ACF Block Style Location](https://github.com/davidwebca/acf-block-style-location). It adds a new Field Group Location type called "ACF Block Variation" (in a new group called "Block Variations"), parses the ACF Blocks you have registered, and build a list of Block Variations you can choose from to assign to your Field Group.

## Installation

Install via [Composer](https://getcomposer.org/download/):

```bash
composer require rynokins/acf-block-variation-location
```

Or simply download zip of this repo and place in your plugins directory!

## Setup

This plugin will parse all of your custom ACF blocks and register a field group location for each block variation you define. Using the [block.json](https://www.advancedcustomfields.com/resources/acf-block-configuration-via-block-json/) setup for registration, you can define your variations like so:

```json
{
    "$schema": "https://advancedcustomfields.com/schemas/json/main/block.json",
    "name": "acf/card-item",
    "title": "Card Item",
    "description": "Single Card Item",
    "category": "custom-layout-category",
    "icon": "columns",
    "acf": {
        "mode": "preview",
        "renderTemplate": "card-item.php"
    },
    "variations": [
        {
            "name": "card-item-default",
            "title": "Card Item",
            "description": "Single Card Item.",
            "isDefault": true,
            "isActive": ["className"],
            "scope": ["inserter"],
            "attributes": {
                "className": "is-card-item"
            }
        },
        {
            "name": "card-item-alt",
            "title": "Alt Card Item",
            "description": "Single Alt Card Item.",
            "isActive": ["className"],
            "scope": ["inserter"],
            "attributes": {
                "className": "is-card-item-alt"
            }
        }
    ]
}
```
The above block variations [Card Item, Card Item Alt] would show in your ACF Location Rules, using the `className` as the identifier for matching the rule to your variation. If you are going to use a different `isActive` attribute, you may need to fiddle around with the match rules to get it to work.

---

## Bug Reports and contributions

All issues can be reported right here on github and I'll take a look at it. I don't intend on maintaining this a lot this the APIs will vastly change in the next few releases of Gutenberg, but please don't hesitate to ask around and create issues on Github. Make sure to give as many details as possible since I'm working full-time and will only look at them once in a while. Feel free to add the code yourself with a pull request.

## License

This code is provided under the [MIT License](https://github.com/davidwebca/acf-block-style-location/blob/master/LICENSE.md).