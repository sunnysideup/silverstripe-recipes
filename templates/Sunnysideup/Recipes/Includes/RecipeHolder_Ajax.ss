<% if $PaginatedRecipes.Exists %>
    <% loop $PaginatedRecipes %>
        <% include Sunnysideup\\Recipes\\Includes\\RecipeSummary %>
    <% end_loop %>
<% end_if %>
