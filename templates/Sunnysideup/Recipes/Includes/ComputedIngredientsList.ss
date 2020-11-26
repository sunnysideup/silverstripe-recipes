<% if $Ingredients %>
<div class="ingredient-list">
    <h3><% if $Title %>$Title<% else %>Ingredients<% end_if %></h3>
    <ul>
    <% loop $Ingredients %>
        <li itemprop="recipeIngredient">$Ingredient</li>
    <% end_loop %>
    </ul>
</div>
<% end_if %>
