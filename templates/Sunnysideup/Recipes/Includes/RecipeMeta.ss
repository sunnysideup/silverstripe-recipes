<% if PublishDate %>
    <p class="blog-post-meta">

        <span itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
            <meta itemprop="name" content="$SiteConfig.Title">
            <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
               <meta itemprop="url" content="$LogoLink">
               <meta itemprop="width" content="444">
               <meta itemprop="height" content="136">
            </span>
        </span>
        <% if $Credits %>
            <% loop $Credits %>
                <meta itemprop="author" content="$Name.XML">
            <% end_loop %>
        <% end_if %>

        <span>
            $PublishDate.Format('MMMM, {o} YYYY')
            <meta itemprop="dateModified" content="$LastEdited" />
            <meta itemprop="datePublished" content="$PublishDate.Format('y-MM-dd')">
        </span>

        <% if $CategoriesFiltered.exists %>
             | Categories:
            <span itemprop="keywords">
                <% loop $CategoriesFiltered %>
                <a href="$Link" title="$Title" itemprop="recipeCategory">$Title</a><% if not Last %>, <% end_if %>
                <% end_loop %>
            </span>
        <% end_if %>

        <% if $TagsFiltered.exists %>
             | Tags:
            <span itemprop="keywords">
                <% loop $TagsFiltered %>
                    <a href="$Link" title="$Title" rel="tag">$Title</a><% if not Last %>, <% end_if %>
                <% end_loop %>
            </span>
        <% end_if %>

        <% if $Comments.exists %>
            | Comments:
            <a href="{$Link}#comments-holder">
                $Comments.count
            </a>;
        <% end_if %>
    </p>
<% end_if %>
