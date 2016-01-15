<p>You have successfully installed MODX Revolution [[++settings_version]]!</p>
<p>Now that MODX is installed you can login to the manager to create your templates, manage content and install third party extras to add functionality to your website. </p>

<h2>New to MODX?</h2>

<p>Pages on a MODX site are called <b>Resources</b>, and are visible on the left-hand side of the manager in the Resources tab. Resources can be nested under other resources, making it easy to create a tree of resources. There are different types of Resources, each with their unique properties. These are:</p>

<ul>
    <li>Documents: you will use these most often, as they contain text or rich content.</li>
    <li>Static Resources: useful for referring to files
    <li>Weblinks: redirect the user to a different URL</li>
    <li>Symlinks: creates a synchronised copy of another resource</li>
</ul>

<p>It's also possible to install other resource types with Extras. <a href="http://modx.com/extras/package/collections">Collections</a> for example, adds a resource type that will show child resources in a grid instead of the tree, making it more suitable for large quantities of resources and blogs.</p>

<p>Building your website is done through a combination of <b>Templates</b>, <b>Template Variables</b>, <b>Chunks</b>, <b>Snippets</b> and <b>Plugins</b>. Collectively these are known as <b>Elements</b>, and can also be found in the left-hand side of the manager, in the Elements tab.</p>

<p><a href="https://rtfm.modx.com/revolution/2.x/making-sites-with-modx/structuring-your-site/templates">Templates</a> contain the outer markup of any page. Each resource can only be assigned to a single template at a time. By adding <a href="https://rtfm.modx.com/revolution/2.x/making-sites-with-modx/customizing-content/template-variables">Template Variables</a> to a template, you can add custom fields for any resource using that particular template. </p>

<p>With <a href="https://rtfm.modx.com/revolution/2.x/making-sites-with-modx/structuring-your-site/chunks">Chunks</a> you can share parts of the markup, such as a header, across different templates. <a href="https://rtfm.modx.com/revolution/2.x/making-sites-with-modx/structuring-your-site/using-snippets">Snippets</a> are pieces of PHP that return dynamic content, such as summaries of other resources or the current date. With snippets, you will often use Chunks to mark up the pieces of content it returns, instead of mixing the PHP and HTML.</p>

<p>Finally, <a href="https://rtfm.modx.com/revolution/2.x/developing-in-modx/basic-development/plugins">Plugins</a> enable more advanced features by hooking into the extensive events system provided by MODX.</p>

<p>To learn more about MODX, be sure to check out the <a href="https://rtfm.modx.com/revolution/2.x/getting-started">Getting Started</a> section in the official documentation.</p>
