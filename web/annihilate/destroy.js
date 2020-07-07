function conf(){
	if(!confirm("Are you sure you wish to delete yourself?")) return false;
	if(!confirm("You must manually forget each conversation, or they will stay there. All other information about you will be deleted automatically.")) return false;
	if(!confirm("This cannot be reversed!\nYou can NOT change your mind about this!\nAre you still sure?")) return false;
	return true;
}

function destroyMe(){
    confReturn = conf();
    if (confReturn == true){
        window.location = "http://audimate.me/web/annihilate/index.php?act=del.confirm.thrice";
    } else {
        window.location = "http://audimate.me/web/login/";			
    }
}