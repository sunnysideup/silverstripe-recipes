<% cached CachingKeyContent %>
    <div id="recipe-holder" class="container">
        <div id="content" class="typography clearfix">
            <div class="typography clearfix">
                $Content
            </div>
            <div class="separator">
                <span class="icon-wrapper">
                    {$SVG('star')}
                </span>
            </div>
            <div class="row grid">
                <% if $PaginatedRecipes.Exists %>
                    <% loop $PaginatedRecipes %>
                        <% include Sunnysideup\\Recipes\\Includes\\RecipeSummary %>
                    <% end_loop %>
                <% else %>
                    <p>There are no recipes to display at the moment, please come back and try again later</p>
                <% end_if %>
            </div>
            <% if $PaginatedRecipes.MoreThanOnePage %>
                <div id="results-loader">
                    <svg width="200px" height="200px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" style="background: rgba(0, 0, 0, 0) none repeat scroll 0% 0%;">'
                        <circle cx="50" cy="50" fill="none" stroke="#C91630" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">'
                            <animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite">

                            </animateTransform>'
                        </circle>
                    </svg>
                    <div>loading more recipes ... </div>
                </div>
            <% end_if %>
            <% with $PaginatedRecipes %>
                <% include Sunnysideup\\Recipes\\Includes\\Pagination %>
            <% end_with %>
        </div>


        <% if $RecipeCategories %>
            <% loop $RecipeCategories %>
                <a class="btn" href="$Link" title="$Title">
                    $Title
                </a>
            <% end_loop %>
        <% end_if %>

    </div>
<% end_cached %>
