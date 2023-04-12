String.prototype.isEmpty = function()
{
	return (this.length === 0 || !this.trim());
};

$.fn.hasAttr = function(name)
{
	return this.attr(name) !== undefined;
};
