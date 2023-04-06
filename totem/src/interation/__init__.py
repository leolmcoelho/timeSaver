import time
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support import expected_conditions as EC 
from selenium.webdriver.support.ui import WebDriverWait



class Interation:
    
    
    def __init__(self, driver:webdriver.Chrome):
        
        self.driver = driver

    
    def write(self, tag:str, message:str, method:str='xpath', time:int=15):
            
            WebDriverWait(self.driver, time).until(
                    EC.element_to_be_clickable((method, tag)))
            element = self.driver.find_element(method, tag)
                        
            element.send_keys(str(message))
            return True
                        

    def click(self, tag:str, method = 'xpath', time=10):
            WebDriverWait(self.driver, time).until(
                    EC.element_to_be_clickable((method, tag)))
            element = self.driver.find_element(method, tag)
                
            element.click()
            return True
        
    
    def key(self, tag:str, key = 'enter',  time:int=15, method:str='xpath'):
                                
        WebDriverWait(self.driver, time).until(
                EC.presence_of_element_located((method, tag)))
        element = self.driver.find_element(method, tag)
                            
        if key == 'enter':
            element.send_keys(Keys.ENTER)

        elif key == 'esc':
            element.send_keys(Keys.ESCAPE)
            
        elif key == 'home':
            element.send_keys(Keys.HOME)
            
        else:
            element.send_keys(key)
            
        
        return True
    
    
    def element(self, tag:str, time:int=15, method:str='xpath'):
        
        WebDriverWait(self.driver, time).until(
                EC.presence_of_element_located((method, tag)))
        element = self.driver.find_element(method, tag)
        
        return element
    
    def locacated(self, tag:str, time:int=15, method:str='xpath'):
        if self.element(tag, time, method):
            return True
    
        else:
            return False
    
    
    def get_attribute(self, tag:str, attribute:str='value', time:int=15, method:str='xpath'):
    
        return self.element(tag, time, method).get_attribute(attribute)
        
        
    def click_js(self, tag:str,time:int=15, method:str='xpath'):
        el = self.element(tag, time, method)
        self.driver.execute_script("arguments[0].click();", el)
       
    
    def verify_page(self, param, time_break = 10):
        initial = time.time()
        
        while time.time() - initial < time_break:
            url = self.driver.current_url
        
            url =  url.split('/')
            if param in url:
                return True
        
        return False
    

    def write_js(self, tag, value ):
        js = f'document.querySelector("{tag}").value = "{value}"'
        self.driver.execute_script(js)
        