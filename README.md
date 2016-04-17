# Fork CMS Documentation Module
[![Slack Status](https://fork-cms.herokuapp.com/badge.svg)](https://fork-cms.herokuapp.com/)

**A documentation module for Fork CMS, used in [the official Fork CMS documentation site](http://docs.fork-cms.com) (Coming soon!). 
Parses markdown files from a git repository and displays them as documentation on a Fork CMS website.**

You will need to store your documentation in markdown (*.md) files in a git repository (e.g. see [forkcms documentation](https://github.com/forkcms/documentation)). 
Once you configured the module settings, the module builds a navigation tree based on the git repository folders and files. 
When you click a navigation item in the frontend part of your site, the content of the markdown file is fetched and parsed to html and displayed on the page. 
The documentation uses (almost) no database interaction and is fully dynamic. Everything is cached to speed up everything and save additional github requests.

---

## Installation

1. Copy the `/src/Backend/Modules/Documentation` folder to your site's `/src/Backend/Modules` folder.
2. Copy the `/src/Frontend/Modules/Documentation` folder to your site's `/src/Frontend/Modules` folder.
3. Browse to your Fork CMS backend.
4. Go to `Settings > Modules`. Click on the install button next to the Documentation module.
5. Have fun!

### Configuring the module settings
1. Go to the Fork CMS backend and then to `Settings > Modules > Documentation`.
2. **Repository info:** You will need the Github organization/username and the name of the repository. You can find these values by just copying the
Github documentation repository url (github.com/**username**/**repository**).
3. **oAuth Token:** Without authentication, Github offers 60 requests per hour (See Github [Rate Limiting](https://developer.github.com/v3/#rate-limiting)). 
When the documentation is not in the cache, a requests gets sent to Github. It could happen that you reach your rate limit and an error gets thrown. 
Therefore, you can generate an oAuth token. Go to your Github profile settings > Personal access tokens > Generate new token. Only select checkbox "public_repo" and generate the token.
Copy and paste the token onto the Fork CMS Documentation settings page. You now have 5000 requests/hour which should be sufficient because we use caching once the contents
of a file are fetched.

### Setting up a git webhook to clear cache
Imagine you update your documentation on Github. Then you would want to see those changes appearing on your documentation site.
Because we use caching, the old version of a page would still show up. You could manually clear the cache (clear `src/Frontend/Cache/Documentation`), or start using a Github webhook to trigger clearing the cache.

To setup a Github webhook:

1. Go to your Github documentation repo settings > Webhooks & services
2. Click the Add webhook button
3. Use the following settings:
  * **Payload URL:** http://domain.com/documentation-page-name/webhook-cache-clear
  * **Content-type:** `application/json`
  * **Secret:** Choose one yourself
  * **Event:** Just the push event (this will include merges and pushes)
4. Every time you merge or push to the documentation repo, a documentation cache clear action will trigger on your website. 

---

## FAQ
- **Do you have a search widget to search our documentation?**
No, but please try [Algolia DocSearch](). [Algolia](https://www.algolia.com/) offers Search-as-a-Service. Think of Algolia very much like you would Stripe. If Stripe makes payment processing a cinch, then Algolia makes advanced search accessible to everyone. 
Algolia is a hosted cloud search solution with easy API access. They offer free (!) documentation search for your documentation projects like
React, Scala, Babel.js, ...  Integrating Algolia DocSearch is really easy. You only have to add a `docsearch.min.css` and `docsearch.min.js` CDN file on
your webpage. They setup a website crawler for your documentation section of the website that runs every 24h, and you receive an API token.
They provide you a javascript snippet with your API token and a CSS selector that you can use on your search input field. It works flawlessly. 
- **Do you support other Git services like Bitbucket or Gitlab, or other locations to store your documentation (FTP, ...)?**
No, not at the moment. But adding Bitbucket or Gitlab support is easy. Add a BitBucketDocumentation class that implements the RepositoryInterface and add a BitBucketAdapter.

---

## Bugs & Feature requests

If you encounter any bugs of have a feature request, please create an issue so we can fix it (or feel free to fix it yourself with a pull-request).

---

## Discussion
- Slack: [Fork CMS Slack channel](https://fork-cms.herokuapp.com)
- Twitter: [@jessedobbelaere](https://www.twitter.com/jessedobbelaere)
- E-mail: <jesse@dobbelaere-ae.be> for any questions or remarks.
