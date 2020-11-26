<% if $MoreThanOnePage %>
	<div class="pagination clearfix">
		<% if $NotFirstPage %>
			<a class="prev" href="{$PrevLink}">Previous</a>
		<% end_if %>

		<% loop $PaginationSummary(4) %>
			<% if $CurrentBool %>
				<span class="current">$PageNum</span>
			<% else %>
				<% if $Link %>
					<a href="$Link" class="inactive">$PageNum</a>
				<% else %>
					<span>...</span>
				<% end_if %>
			<% end_if %>
		<% end_loop %>

		<% if $NotLastPage %>
			<a class="next" href="{$NextLink}">Next</a>
		<% end_if %>
	</div>
<% end_if %>
