<?

class HomeView extends BaseView
{
	public function openHome($template_data)
	{
		Render::open_html();
		Render::default_javascript();		
		$this->layout($template_data);
		Render::div_ajax_message();		
		Render::close_html();
	}
}
	
	
