# TYPO3 Extension `woit_nsreadtime`

Our new TYPO3 extension includes a view helper that calculates the estimated reading time for news extension, 
enhancing the user experience for your readers.

## Installation

Install this extension via `composer req woit/woit-nsreadtime` and activate
the extension in the Extension Manager of your TYPO3 installation.

## Adding to your own sitepackage

Add namespace in your code

```html
<html xmlns:time="http://typo3.org/ns/Woit/WoitNsreadtime/ViewHelpers" data-namespace-typo3-fluid="true"></html>
```

or

```html
{namespace time=Woit\WoitNsreadtime\ViewHelpers}
```

You can use either one of them to add namespace in your template

In your news template, add the following code:

```html
<time:readtime newsId="{CURRENT_NEWS_ID}"  />
```

Replace CURRENT_NEWS_ID woith the ID of your current news item. This code will render the estimated reading time for your news post based on the content.