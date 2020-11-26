var Recipes = {
  recipeArchive: jQuery('#recipe-holder'),

  pagination: jQuery('.pagination'),

  contentGrid: jQuery('#content .grid'),

  loader: jQuery('#results-loader'),

  loadingNextPage: false,

  init: function () {
    if (Recipes.recipeArchive.length && Recipes.pagination.length) {
      Recipes.pagination.css('visibility', 'hidden')
      Recipes.scrollListener()
    }
  },

  // activateMasonry: function (itemSelector) {
  //   var Masonry = require('masonry-layout')
  //   var msnry = new Masonry('.grid', {
  //     itemSelector: itemSelector,
  //   })
  // },

  // masonry: function () {
  //   Recipes.activateMasonry(masonryClass)
  //   //to deal with lazy loading on images we need to reactivate the masonry everytime a new image is loaded
  //   jQuery('.featured-image img').on('load', function () {
  //     Recipes.activateMasonry('.recipe-summary')
  //   })
  // },

  scrollListener: function () {
    jQuery(document).on('scroll', function () {
      let currentLink = Recipes.pagination.find('.current')
      let nextLink = currentLink.next(':not(.next)')
      if (
        Recipes.elementIsOnScreen(Recipes.pagination) &&
        nextLink.length &&
        !Recipes.loadingNextPage
      ) {
        Recipes.loadingNextPage = true
        currentLink.removeClass('current')
        nextLink.addClass('current')
        let href = nextLink.attr('href')
        jQuery.ajax({
          url: href,
          beforeSend: function () {
            Recipes.loader.css('display', 'flex')
          },
          success: function (result) {
            Recipes.loader.css('display', 'none')
            Recipes.contentGrid.append(result)
            //Recipes.masonry()
            Recipes.loadingNextPage = false
          },
        })
      }
    })
  },

  elementIsOnScreen: function (el) {
    const vpH = jQuery(window).height()
    const st = jQuery(window).scrollTop()
    const y = el.offset().top
    const elementHeight = el.height()
    return y < vpH + st && y > st - elementHeight
  },
}

module.exports = Recipes
