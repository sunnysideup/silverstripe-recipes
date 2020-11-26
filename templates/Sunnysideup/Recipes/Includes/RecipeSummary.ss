<article class="recipe-summary col-xs-4 clearfix">
    <div class="featured-image">
        <a href="$Link">$FeaturedImage.PerfectCMSImageTag('FeaturedImageThumbMedium', false, $Title)</a>
    </div>

    <div class="content-wrapper">
        <h2>
            <a href="$Link">
                <% if $MenuTitle %>$MenuTitle
                <% else %>$Title<% end_if %>
            </a>
        </h2>
    </div>
</article>
