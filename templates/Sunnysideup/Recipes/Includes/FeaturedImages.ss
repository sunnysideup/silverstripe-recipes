<div class="carousel-wrapper clearfix">
    <div class="featured-carousel clearfix">
        <% if $FeaturedImage && not $HideFeaturedImageOnEntryPage %>
        <div class="featured-carousel-item">
            <div class="featured-image" style="background-image:url($FeaturedImage.PerfectCMSImageLink('FeaturedImage'))">
            </div>
        </div>
        <% end_if %>
        <% if $FeaturedImage2 %>
        <div class="featured-carousel-item">
            <div class="featured-image" style="background-image:url($FeaturedImage2.PerfectCMSImageLink('FeaturedImage'))">
            </div>
        </div>
        <% end_if %>
        <% if $FeaturedImage3 %>
        <div class="featured-carousel-item">
            <div class="featured-image" style="background-image:url($FeaturedImage3.PerfectCMSImageLink('FeaturedImage'))">
            </div>
        </div>
        <% end_if %>
        <% if $FeaturedImage4 %>
        <div class="featured-carousel-item">
            <div class="featured-image" style="background-image:url($FeaturedImage4.PerfectCMSImageLink('FeaturedImage'))">
            </div>
        </div>
        <% end_if %>
        <% if $FeaturedImage5 %>
        <div class="featured-carousel-item">
            <div class="featured-image" style="background-image:url($FeaturedImage5.PerfectCMSImageLink('FeaturedImage'))">
            </div>
        </div>
        <% end_if %>
    </div>
    <span class="featured-carousel-nav-prev">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
            <path d="M6.293 13.707l-5-5c-0.391-0.39-0.391-1.024 0-1.414l5-5c0.391-0.391 1.024-0.391 1.414 0s0.391 1.024 0 1.414l-3.293 3.293h9.586c0.552 0 1 0.448 1 1s-0.448 1-1 1h-9.586l3.293 3.293c0.195 0.195 0.293 0.451 0.293 0.707s-0.098 0.512-0.293 0.707c-0.391 0.391-1.024 0.391-1.414 0z"></path>
        </svg>
    </span>
    <span class="featured-carousel-nav-next">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
            <path d="M9.707 13.707l5-5c0.391-0.39 0.391-1.024 0-1.414l-5-5c-0.391-0.391-1.024-0.391-1.414 0s-0.391 1.024 0 1.414l3.293 3.293h-9.586c-0.552 0-1 0.448-1 1s0.448 1 1 1h9.586l-3.293 3.293c-0.195 0.195-0.293 0.451-0.293 0.707s0.098 0.512 0.293 0.707c0.391 0.391 1.024 0.391 1.414 0z"></path>
        </svg>
    </span>
</div>
