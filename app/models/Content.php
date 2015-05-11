<?php
class Content extends AppModel {

	protected $table = "content";
	protected $fillable = ["type_id","name","content","is_published","published_at","unpublished_at"];
	protected $appends = ['author','contentTypeName','status','parsedContent'];

	public function contentType() { return $this->belongsTo('ContentType','type_id'); }
	public function user() { return $this->belongsTo('User','created_by'); }

	public function getAuthorAttribute() {
        if (!array_key_exists("user", $this->relations)) $this->load("user");
        $user = $this->getRelation("user");
		return $user->name;
	}

	public function getParsedContentAttribute() {
		return Markdown::string($this->content);
	}

	public function getContentTypeNameAttribute() {
        if (!array_key_exists("contentType", $this->relations)) $this->load("contentType");
        $contentType = $this->getRelation("contentType");
		return $contentType->name;
	}

	public function getStatusAttribute() {
		$now = new DateTime();

		$published = (bool) $this->is_published;

		if (strlen($this->published_at)) {
			$published_at = new DateTime($this->published_at);
			$published = ($now >= $published_at);
		}
		if (strlen($this->unpublished_at)) {
			$unpublished_at = new DateTime($this->unpublished_at);	
			$published = ($now <= $unpublished_at);
		}
		return $published;
	}


	public function scopeOrdered($query) { return $query->orderBy('created_at', 'desc'); }
	public function scopePublicPost($query) { return $query->where("type_id","=",1); }
	public function scopeDashboardPost($query) { return $query->where("type_id","=",2); }
	public function scopePage($query) { return $query->where("type_id","=",3); }

}