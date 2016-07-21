/**
* Gets the current browser's width and height.
* redefine the items_per_page according to the size.
* Then, it will call getTranscribableDocumentsRequest to get documents.
*/
function getItemsPerPage(width, height){
    var items_per_page = 6;

    if(height <= 600){
        items_per_page = 4;
    }
    if(height > 600 && height <= 630){
        items_per_page = 5;
    }
    if(height > 630 && height <= 700){
        items_per_page = 6;
    }
    if(height >700 && height <= 780){
        items_per_page = 7;
    }
    if(height > 780 && height <= 830){
        items_per_page = 8;
    }
    if(height >830 && height <= 880){
        items_per_page = 9;
    }
    if(height >880 && height <= 935){
        items_per_page = 10;
    }
    if(height > 935){
        items_per_page= 11;
    }


    return items_per_page;

}

function getStartNumForPagination(total_pages, current_page){

    var startNum = 0;

    if(total_pages > 5){
        if(current_page < 3){
            startNum = 0;
        }
        else if(total_pages- current_page < 2){
            startNum = total_pages - 5;
        }
        else{
            startNum = current_page -3;
        }
    }


    return startNum;
}

function getEndNumForPagination(total_pages, startNum){
    var endNum = startNum + 5;
    if(total_pages < 5){
        endNum = total_pages;
    }
    return endNum;

}
