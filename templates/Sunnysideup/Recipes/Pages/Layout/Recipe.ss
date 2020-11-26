<div class="container">
    <div id="content">
        <article class="typography" itemscope itemtype="http://schema.org/Recipe">
            <% cached CachingKeyContent %>
                <% include Sunnysideup\\Recipes\\Includes\\FeaturedImages %>

                <h2 class="entry-title clearfix">
                    <span itemprop="name">$Title</span>
                    <% if RecipePDF %>
                        <a href="$RecipePDF.URL" class="externalLink recipe-link">
                            <img src="themes/base/images/download-recipe.png" alt="Download Recipe">
                        </a>
                    <% end_if %>
                </h2>
                <% if $CuisineType %>
                    <meta itemprop="recipeCuisine" content="$CuisineType">
                <% end_if %>

                <% if PrepTimeInMinutes || CookingTimeInMinutes %>
                    <div id="recipe-times" class="clearfix">
                        <% if $PrepTimeInMinutes %>
                            <div>
                                <span>
                                    {$SVG('blender')}
                                    <meta itemprop="prepTime" content="$PrepTimeInMinutesAsIso">
                                    <strong>Preparation Time:</strong>&nbsp;<span>$PrepTimeInMinutes minutes</span>
                                </span>
                            </div>
                        <% end_if %>

                        <% if $CookingTimeInMinutes %>
                            <div>
                                <span>
                                    {$SVG('oven')}
                                    <meta itemprop="cookTime" content="$CookingTimeInMinutesAsIso">
                                    <strong>Cooking Time:</strong>&nbsp;<span>$CookingTimeInMinutes minutes</span>
                                </span>
                            </div>
                        <% end_if %>
                    </div>
                <% end_if %>

                <% if $RecipeContributorLink || $ContributorTitle %>
                    <p>
                        <strong>Recipe By</strong>
                        <% if $RecipeContributorLink %>
                            <a href="$RecipeContributorLink">
                                <span itemprop="author">
                                    <% if $ContributorTitle %>
                                        $ContributorTitle
                                    <% else %>
                                        $RecipeContributorLink
                                    <% end_if %>
                                </span>
                            </a>
                        <% else_if $ContributorTitle %>
                            <span itemprop="author">$ContributorTitle</span>
                        <% end_if %>
                    </p>
                <% end_if %>

                <% if Serves || ServesDescription %>
                    <div  itemprop="recipeYield">
                        <% if Serves %><p class="serves"><strong>Serves: <span>$Serves</span></strong></p><% end_if %>
                        <% if ServesDescription %><p class="serves-description"><strong>$ServesDescription</strong></p><% end_if %>
                    </div>
                <% end_if %>

                <% if Summary %>
                    <span itemprop="description">$Summary</span>
                <% end_if %>


                $IngredientList(1)
                $IngredientList(2)
                $IngredientList(3)
                $IngredientList(4)
                $IngredientList(5)

                <% if $DirectionsHeader && $Content %>
                    <h3>$DirectionsHeader</h3>
                <% end_if %>

                <% if $Content %>
                    <div class="post-content" itemprop="recipeInstructions">
                        $Content
                    </div>
                <% end_if %>

                <% if $FeaturedVideo %>
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/$FeaturedVideo" allowfullscreen></iframe>
                    </div>
                <% end_if %>


                <% include Sunnysideup\\Recipes\\Includes\\RecipeMeta %>
                <%-- include ShareThis --%>
                <% include Sunnysideup\\Recipes\\Includes\\RelatedPosts %>
            <% end_cached %>
        </article>
    </div>
</div>
